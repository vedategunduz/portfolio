const STORAGE_VISITOR_KEY = 'blog_visitor_uuid';
const STORAGE_SESSION_KEY = 'blog_session_id';
const SESSION_TIMEOUT_MS = 30 * 60 * 1000;
const HEARTBEAT_MS = 15000;
const ACTIVE_INTERACTION_WINDOW_MS = 30000;

function uuidv4() {
    if (window.crypto?.randomUUID) {
        return window.crypto.randomUUID();
    }

    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
        const r = (Math.random() * 16) | 0;
        const v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
}

function getOrCreateVisitorUuid() {
    let visitor = localStorage.getItem(STORAGE_VISITOR_KEY);
    if (!visitor) {
        visitor = uuidv4();
        localStorage.setItem(STORAGE_VISITOR_KEY, visitor);
    }

    return visitor;
}

function getOrCreateSessionId() {
    const raw = localStorage.getItem(STORAGE_SESSION_KEY);
    const now = Date.now();
    if (raw) {
        try {
            const parsed = JSON.parse(raw);
            if (parsed?.id && parsed?.lastSeen && now - parsed.lastSeen < SESSION_TIMEOUT_MS) {
                parsed.lastSeen = now;
                localStorage.setItem(STORAGE_SESSION_KEY, JSON.stringify(parsed));
                return parsed.id;
            }
        } catch {
            // no-op
        }
    }

    const fresh = { id: uuidv4(), lastSeen: now };
    localStorage.setItem(STORAGE_SESSION_KEY, JSON.stringify(fresh));

    return fresh.id;
}

function updateSessionLastSeen() {
    const raw = localStorage.getItem(STORAGE_SESSION_KEY);
    if (!raw) return;
    try {
        const parsed = JSON.parse(raw);
        parsed.lastSeen = Date.now();
        localStorage.setItem(STORAGE_SESSION_KEY, JSON.stringify(parsed));
    } catch {
        // no-op
    }
}

function toInt(value, fallback = 0) {
    const parsed = Number.parseInt(String(value), 10);
    return Number.isFinite(parsed) ? parsed : fallback;
}

export function initBlogAnalytics() {
    const context = window.__BLOG_ANALYTICS_CONTEXT;
    if (!context?.enabled || !context?.postId || !context?.postSlug) {
        return;
    }

    const visitorUuid = getOrCreateVisitorUuid();
    const sessionId = getOrCreateSessionId();
    const viewUuid = uuidv4();
    const startedAt = new Date();

    const state = {
        maxScrollPercent: 0,
        progressPercent: 0,
        activeTimeSeconds: 0,
        totalTimeSeconds: 0,
        lastHeartbeatAt: Date.now(),
        firstInteractionAt: null,
        lastInteractionAt: null,
        hasStarted: false,
        heartbeatId: null,
    };

    const commonPayload = () => ({
        visitor_uuid: visitorUuid,
        session_id: sessionId,
        view_uuid: viewUuid,
        post_id: context.postId,
        post_slug: context.postSlug,
        url: window.location.href,
        landing_url: window.location.href,
        referrer: document.referrer || null,
        utm_source: new URLSearchParams(window.location.search).get('utm_source'),
        utm_medium: new URLSearchParams(window.location.search).get('utm_medium'),
        utm_campaign: new URLSearchParams(window.location.search).get('utm_campaign'),
        utm_term: new URLSearchParams(window.location.search).get('utm_term'),
        utm_content: new URLSearchParams(window.location.search).get('utm_content'),
        screen_width: window.screen?.width ?? null,
        screen_height: window.screen?.height ?? null,
        viewport_width: window.innerWidth,
        viewport_height: window.innerHeight,
    });

    const send = (endpoint, payload, keepalive = false) =>
        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ event_uuid: uuidv4(), occurred_at: new Date().toISOString(), ...payload }),
            keepalive,
        }).catch(() => undefined);

    const documentHeight = () =>
        Math.max(
            document.body.scrollHeight,
            document.documentElement.scrollHeight,
            document.body.offsetHeight,
            document.documentElement.offsetHeight
        );

    const computeScrollPercent = () => {
        const viewport = window.innerHeight || 0;
        const maxScrollable = Math.max(documentHeight() - viewport, 1);
        const current = Math.max(window.scrollY || window.pageYOffset || 0, 0);
        return Math.max(0, Math.min(100, Math.round((current / maxScrollable) * 100)));
    };

    const updateProgress = () => {
        const scrollPercent = computeScrollPercent();
        state.maxScrollPercent = Math.max(state.maxScrollPercent, scrollPercent);
        state.progressPercent = state.maxScrollPercent;
    };

    const markInteraction = () => {
        const now = Date.now();
        if (!state.firstInteractionAt) {
            state.firstInteractionAt = now;
        }
        state.lastInteractionAt = now;
        updateSessionLastSeen();
    };

    const onHeartbeat = () => {
        updateProgress();
        const now = Date.now();
        const elapsedSeconds = Math.max(0, Math.round((now - state.lastHeartbeatAt) / 1000));
        state.lastHeartbeatAt = now;
        state.totalTimeSeconds = Math.max(0, Math.round((now - startedAt.getTime()) / 1000));

        const recentlyInteracted = state.lastInteractionAt
            ? now - state.lastInteractionAt <= ACTIVE_INTERACTION_WINDOW_MS
            : false;
        const isActive = !document.hidden && recentlyInteracted;
        const activeDelta = isActive ? elapsedSeconds : 0;
        state.activeTimeSeconds += activeDelta;

        send(context.endpoints.heartbeat, {
            ...commonPayload(),
            active_time_delta: activeDelta,
            max_scroll_percent: state.maxScrollPercent,
            reading_progress_percent: state.progressPercent,
        });
    };

    const sendInteraction = (interactionType) => {
        send(context.endpoints.interaction, {
            ...commonPayload(),
            interaction_type: interactionType,
        });
    };

    const bootstrap = () => {
        if (state.hasStarted) {
            return;
        }
        state.hasStarted = true;
        updateProgress();

        const navEntry = performance.getEntriesByType('navigation')[0];
        send(context.endpoints.start, {
            ...commonPayload(),
            view_started_at: startedAt.toISOString(),
            max_scroll_percent: state.maxScrollPercent,
            reading_progress_percent: state.progressPercent,
            load_time_ms: toInt(navEntry?.loadEventEnd, 0),
            dom_ready_ms: toInt(navEntry?.domContentLoadedEventEnd, 0),
            time_to_first_interaction_ms: 0,
        });

        state.heartbeatId = window.setInterval(onHeartbeat, HEARTBEAT_MS);
    };

    const endView = () => {
        if (!state.hasStarted) {
            return;
        }
        if (state.heartbeatId) {
            window.clearInterval(state.heartbeatId);
            state.heartbeatId = null;
        }
        updateProgress();
        state.totalTimeSeconds = Math.max(0, Math.round((Date.now() - startedAt.getTime()) / 1000));

        send(
            context.endpoints.end,
            {
                ...commonPayload(),
                view_ended_at: new Date().toISOString(),
                total_time_seconds: state.totalTimeSeconds,
                active_time_seconds: state.activeTimeSeconds,
                reading_progress_percent: state.progressPercent,
                time_to_first_interaction_ms: state.firstInteractionAt
                    ? Math.max(0, Math.round((state.firstInteractionAt - startedAt.getTime()) / 1000) * 1000)
                    : null,
            },
            true
        );
    };

    bootstrap();

    window.addEventListener('scroll', () => {
        markInteraction();
        updateProgress();
    }, { passive: true });
    window.addEventListener('click', markInteraction, { passive: true });
    window.addEventListener('keydown', markInteraction, { passive: true });
    window.addEventListener('mousemove', markInteraction, { passive: true });
    window.addEventListener('touchstart', markInteraction, { passive: true });

    document.querySelectorAll('.toc a').forEach((link) => {
        link.addEventListener('click', () => sendInteraction('toc_click'));
    });

    document.querySelectorAll('article a').forEach((link) => {
        link.addEventListener('click', () => {
            const href = link.getAttribute('href') || '';
            if (href.startsWith('http') && !href.includes(window.location.host)) {
                sendInteraction('external_link_click');
            } else {
                sendInteraction('internal_link_click');
            }
        });
    });

    document.addEventListener('copy', () => sendInteraction('copy'));

    document.querySelectorAll('[data-share-click]').forEach((button) => {
        button.addEventListener('click', () => sendInteraction('share_click'));
    });

    window.addEventListener('pagehide', endView);
    window.addEventListener('beforeunload', endView);
}
