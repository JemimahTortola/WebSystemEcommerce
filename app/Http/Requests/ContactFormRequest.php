<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\.]+$/u'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'min:5', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            
            // Honeypot fields
            'website' => ['nullable', 'string', 'max:0'],
            'phone2' => ['nullable', 'string', 'max:0'],
            
            // Timestamp
            'form_timestamp' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Please enter a valid name (letters only).',
            'message.min' => 'Your message is too short.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Honeypot check
            if (!empty($this->input('website')) || !empty($this->input('phone2'))) {
                $validator->errors()->add('honeypot', 'Submission rejected.');
                return;
            }

            // Timestamp check - form must take at least 3 seconds
            $timestamp = $this->input('form_timestamp');
            if ($timestamp && (time() - (int)$timestamp) < 3) {
                $validator->errors()->add('form_timestamp', 'Please take your time filling out the form.');
            }

            // Check for spam patterns in message
            $message = $this->input('message');
            if ($message) {
                // Check for excessive URLs (spam indicator)
                $urlCount = preg_match_all('/https?:\/\/[^\s]+/i', $message);
                if ($urlCount > 2) {
                    $validator->errors()->add('message', 'Please remove excessive links from your message.');
                }
                
                // Check for excessive caps (spam indicator)
                $capsRatio = strlen(preg_replace('/[^A-Z]/', '', $message)) / strlen($message);
                if ($capsRatio > 0.5 && strlen($message) > 20) {
                    $validator->errors()->add('message', 'Please avoid using excessive capital letters.');
                }
            }

            // Check subject for spam keywords
            $subject = $this->input('subject');
            if ($subject) {
                $spamKeywords = ['casino', 'viagra', 'lottery', 'winner', 'congratulations', 'click here', 'act now', 'limited time'];
                foreach ($spamKeywords as $keyword) {
                    if (stripos($subject, $keyword) !== false) {
                        $validator->errors()->add('subject', 'Invalid subject.');
                        break;
                    }
                }
            }
        });
    }
}
