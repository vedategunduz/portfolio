<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|min:10|max:5000')]
    public string $message = '';

    public bool $isSubmitting = false;
    public bool $showSuccess = false;
    public bool $showError = false;
    public string $errorMessage = '';

    public function resetForm()
    {
        $this->reset(['name', 'email', 'message', 'showSuccess', 'showError']);
    }

    public function submit()
    {
        $this->showError = false;
        $this->isSubmitting = true;

        try {
            // Validate form
            $validated = $this->validate();

            // TODO: Burada email gönderebilir veya DB'ye kaydedebilirsiniz
            // Mail::to('your-email@example.com')->send(new ContactMail($validated));
            // ContactRequest::create($validated);

            $this->showSuccess = true;
            $this->resetForm();

            // Başarı mesajını 5 saniye sonra gizle
            $this->dispatch('success-shown');
        } catch (\Throwable $e) {
            $this->showError = true;
            $this->errorMessage = 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
