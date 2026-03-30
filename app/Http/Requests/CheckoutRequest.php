<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\.]+$/u'],
            'shipping_phone' => ['required', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]{10,20}$/'],
            'shipping_address' => ['required', 'string', 'min:10', 'max:500'],
            'payment_method' => ['required', 'string', 'in:cash,card,paypal'],
            'notes' => ['nullable', 'string', 'max:1000'],
            
            // Honeypot fields - should be empty
            'website' => ['nullable', 'string', 'max:0'],
            'phone2' => ['nullable', 'string', 'max:0'],
            
            // Timestamp - to detect fast submissions (bots)
            'form_timestamp' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_name.regex' => 'Please enter a valid name (letters, spaces, hyphens only).',
            'shipping_phone.regex' => 'Please enter a valid phone number.',
            'shipping_address.min' => 'Please enter a complete address.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Honeypot check - if filled, it's likely a bot
            if (!empty($this->input('website')) || !empty($this->input('phone2'))) {
                $validator->errors()->add('honeypot', 'Submission rejected.');
                return;
            }

            // Timestamp check - form must take at least 3 seconds to fill (humans)
            $timestamp = $this->input('form_timestamp');
            if ($timestamp && (time() - (int)$timestamp) < 3) {
                $validator->errors()->add('form_timestamp', 'Please take your time filling out the form.');
            }

            // Timestamp shouldn't be in the future
            if ($timestamp && (int)$timestamp > time() + 60) {
                $validator->errors()->add('form_timestamp', 'Invalid form submission timing.');
            }

            // Validate phone number format more strictly
            $phone = $this->input('shipping_phone');
            if ($phone) {
                // Remove all non-digits for validation
                $digitsOnly = preg_replace('/\D/', '', $phone);
                
                // Phone should have between 10-15 digits
                if (strlen($digitsOnly) < 10 || strlen($digitsOnly) > 15) {
                    $validator->errors()->add('shipping_phone', 'Phone number must have 10-15 digits.');
                }
            }

            // Validate address patterns - detect gibberish or suspicious patterns
            $address = $this->input('shipping_address');
            if ($address) {
                // Address should have at least one number
                if (!preg_match('/\d/', $address)) {
                    $validator->errors()->add('shipping_address', 'Please include a house/building number in your address.');
                }
                
                // Check for common address keywords
                $validAddressPatterns = ['street', 'st.', 'avenue', 'ave', 'road', 'rd', 'boulevard', 'blvd', 'drive', 'dr', 'lane', 'ln', 'way', 'court', 'ct', 'apartment', 'apt', 'suite', 'floor', 'building'];
                $hasValidPattern = false;
                $addressLower = strtolower($address);
                foreach ($validAddressPatterns as $pattern) {
                    if (str_contains($addressLower, $pattern)) {
                        $hasValidPattern = true;
                        break;
                    }
                }
                
                // If no valid pattern and address is short, flag it
                if (!$hasValidPattern && strlen($address) < 20) {
                    $validator->errors()->add('shipping_address', 'Please enter a complete street address.');
                }
            }

            // Check for excessive repetition (bots often use repetitive patterns)
            $name = $this->input('shipping_name');
            if ($name && $this->hasExcessiveRepetition($name)) {
                $validator->errors()->add('shipping_name', 'Please enter a valid name.');
            }
        });
    }

    /**
     * Check if a string has excessive character repetition (bot indicator)
     */
    private function hasExcessiveRepetition(string $str): bool
    {
        // Check for same character repeated more than 4 times
        if (preg_match('/(.)\1{4,}/', $str)) {
            return true;
        }
        
        // Check for repeated word patterns (e.g., "test test test test")
        $words = explode(' ', $str);
        if (count($words) >= 2) {
            $uniqueWords = array_unique($words);
            if (count($words) > 2 && count($uniqueWords) === 1) {
                return true;
            }
        }
        
        return false;
    }
}
