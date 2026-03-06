import { createIcons } from 'lucide';
import { Sun, Moon, Eye, Users, Mail, Inbox, Check, Ban, AlertTriangle, Info, X } from 'lucide';
import { initThemeToggle } from './core/theme-toggle.js';
import { Dialog } from './ui/dialog.js';
import { getHttp } from './core/http.js';

const adminIcons = { Sun, Moon, Eye, Users, Mail, Inbox, Check, Ban, AlertTriangle, Info, X };

window.getHttp = getHttp;
window.Dialog = Dialog;
window.createIcons = createIcons;
window.lucideIcons = adminIcons;

initThemeToggle(createIcons, adminIcons);

createIcons({
    attrs: { width: 16, height: 16 },
    icons: adminIcons,
});
