# 3-Step Appointment Booking System with Email Verification

## Overview

The appointment booking modal has been upgraded to a 3-step process with email verification for security.

## Steps

### Step 1: Customer Information & Service Selection

- Customer enters their full name, email, and phone number
- Customer selects the dental service they need
- **Email verification code is sent automatically when proceeding to Step 2**

### Step 2: Date & Time Selection

- Customer selects the appointment date
- System displays available time slots based on:
    - Dentist availability
    - Service duration
    - Existing appointments
- Customer selects their preferred time slot

### Step 3: Email Verification

- Customer enters the 4-character verification code sent to their email
- Code is valid for 15 minutes
- Upon successful verification, the appointment is confirmed

## New Files Created

### Backend Components

1. **`app/Livewire/BookAppointmentModal.php`** - Main Livewire component handling the 3-step flow
2. **`app/Models/VerificationCode.php`** - Model for email verification codes
3. **`app/Notifications/AppointmentVerificationCode.php`** - Email notification with the code
4. **`app/Console/Commands/CleanupExpiredVerificationCodes.php`** - Cleanup command for old codes

### Frontend

5. **`resources/views/livewire/book-appointment-modal.blade.php`** - The 3-step modal view

### Database

6. **`database/migrations/2026_01_20_004641_create_verification_codes_table.php`** - Verification codes table
7. **`database/migrations/2026_01_20_004851_add_email_phone_to_appointments_table.php`** - Added email/phone fields

## Modified Files

1. **`app/Models/Appointment.php`** - Added `email` and `phone` to fillable fields
2. **`app/Services/AppointmentAvailabilityService.php`** - Added `getAvailableSlots()` method
3. **`resources/views/welcome.blade.php`** - Updated to use new Livewire component
4. **`bootstrap/app.php`** - Added scheduled task for cleanup

## Email Configuration

Make sure your `.env` file has proper email configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@dentio.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Testing Email Locally

For development, you can use Mailtrap or Log driver:

```env
MAIL_MAILER=log
```

Emails will be logged to `storage/logs/laravel.log`

## Database Schema

### verification_codes Table

- `id` - Primary key
- `email` - Customer email
- `code` - 4-character verification code
- `expires_at` - Expiration timestamp (15 minutes from creation)
- `used` - Boolean flag to prevent reuse
- `created_at` / `updated_at` - Timestamps

### appointments Table (updated)

- Added: `email` - Customer email (nullable)
- Added: `phone` - Customer phone (nullable)

## Scheduled Tasks

A daily cleanup task removes expired and used verification codes:

```bash
php artisan verification:cleanup
```

This runs automatically via Laravel's scheduler. Make sure to add to cron:

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Usage Flow

1. Customer clicks "Book Appointment" button on a dentist card
2. **Step 1**: Enters personal info and selects service → Clicks "Continue"
3. System sends 4-char code to customer's email
4. **Step 2**: Selects date and available time slot → Clicks "Continue"
5. **Step 3**: Enters verification code from email → Clicks "Confirm Appointment"
6. System verifies code and creates the appointment
7. Success message is displayed and modal closes

## Security Features

- **Email Verification**: Ensures customer owns the email address
- **Code Expiration**: Codes expire after 15 minutes
- **Single Use**: Codes can only be used once
- **Time Slot Validation**: Prevents double booking
- **Automatic Cleanup**: Old codes are removed daily

## Manual Commands

### Clean up verification codes

```bash
php artisan verification:cleanup
```

### Run migrations

```bash
php artisan migrate
```

## Troubleshooting

### Emails not sending

1. Check `.env` mail configuration
2. Test with: `php artisan tinker` then:
    ```php
    Mail::raw('Test email', function ($message) {
        $message->to('test@example.com')->subject('Test');
    });
    ```

### Modal not opening

1. Clear Livewire cache: `php artisan livewire:clear`
2. Check browser console for JavaScript errors
3. Ensure Alpine.js is loaded

### Time slots not showing

1. Check `AppointmentAvailabilityService::getAvailableSlots()`
2. Verify service has a duration set
3. Check for existing appointments that might block slots

## Customization

### Change working hours

Edit `AppointmentAvailabilityService::getAvailableSlots()`:

```php
$startHour = 9;  // Change start time
$endHour = 17;   // Change end time
```

### Change code length

Edit `BookAppointmentModal::sendVerificationCode()`:

```php
$code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6)); // 6 chars
```

### Change expiration time

Edit `BookAppointmentModal::sendVerificationCode()`:

```php
'expires_at' => now()->addMinutes(30), // 30 minutes
```

## Future Enhancements

- [ ] Resend verification code button
- [ ] SMS verification as alternative
- [ ] Appointment reminders via email
- [ ] Customer appointment history
- [ ] Calendar integration (Google Calendar, iCal)
