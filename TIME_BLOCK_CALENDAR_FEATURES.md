# Time-Block Calendar Features

## Overview

The calendar now displays appointments as time blocks that automatically span based on the service duration selected by the customer.

## Key Features Implemented

### 1. **Automatic Duration Calculation**

- When a customer books an appointment with a service (e.g., 60 minutes), the calendar automatically calculates the end time
- Example: Booking at 8:00 AM with a 1-hour service → displays from 8:00 AM to 9:00 AM

### 2. **Visual Time Blocks**

- Appointments appear as colored blocks spanning multiple time slots
- Block height is dynamically calculated based on service duration
- Each block shows:
    - Patient name with status icon
    - Service name
    - Start time → End time
    - Duration in minutes
    - Dentist name
    - Status badge (Pending, Confirmed, Completed, Cancelled)

### 3. **Smart Slot Management**

- Time slots occupied by a spanning appointment are visually marked
- Prevents duplicate display of the same appointment in multiple slots
- Occupied slots show a subtle background color
- Empty slots show "Click to add" on hover

### 4. **Enhanced Visual Design**

- Color-coded by status:
    - **Pending**: Amber/Orange gradient
    - **Confirmed**: Green gradient
    - **Completed**: Blue gradient
    - **Cancelled**: Red gradient
- Left border indicator showing appointment duration
- Smooth hover effects with shadow and scale animation
- Backdrop blur effect for modern glass-morphism look

### 5. **Statistics Dashboard**

- Real-time statistics showing:
    - Total appointments for the day
    - Total hours booked
    - Number of confirmed appointments
    - Number of pending appointments
- Visual cards with gradient icons

### 6. **Interactive Features**

- Click on any appointment block to edit
- Hover effects to highlight appointments
- Click on empty slots to add new appointments
- Tooltip showing full appointment duration

## How It Works

### Backend (DayCalendar.php)

```php
// Calculates end time based on service duration
$durationMinutes = $appointment->service->duration; // e.g., 60 minutes
$startTime = Carbon::parse($appointment->appointment_date);
$endTime = $startTime->copy()->addMinutes($durationMinutes);

// Returns formatted data
'start_time' => $startTime->format('g:i A'),  // 8:00 AM
'end_time' => $endTime->format('g:i A'),      // 9:00 AM
'duration_minutes' => $durationMinutes,        // 60
```

### Frontend (day-calendar.blade.php)

```php
// Calculates how many time slots the appointment should span
$slotsSpanned = ceil($durationMinutes / $slotDuration);
$heightInPixels = ($slotsSpanned * 64) - 4; // Dynamic height

// Prevents rendering in occupied slots
$isOccupied = /* checks if slot is in middle of another appointment */;
```

## Example Scenarios

### Scenario 1: 1-Hour Dental Cleaning

- **Service Duration**: 60 minutes
- **Booking Time**: 8:00 AM
- **Display**: Block from 8:00 AM to 9:00 AM (spans 2 slots if using 30-min intervals)

### Scenario 2: 30-Minute Consultation

- **Service Duration**: 30 minutes
- **Booking Time**: 10:00 AM
- **Display**: Block from 10:00 AM to 10:30 AM (spans 1 slot)

### Scenario 3: 90-Minute Root Canal

- **Service Duration**: 90 minutes
- **Booking Time**: 2:00 PM
- **Display**: Block from 2:00 PM to 3:30 PM (spans 3 slots)

## Customization Options

### Calendar Settings (Adjustable in UI)

- **Work Start Time**: Default 8:00 AM
- **Work End Time**: Default 4:00 PM (16:00)
- **Slot Duration**: 15, 30, 45, 60, 90, or 120 minutes
- **Save Settings**: Persists for the selected dentist

### Visual Customization

All colors use the brand gradient: `#4988C4` → `#6BA3D8`

## Benefits

1. **Clear Visual Schedule**: See at a glance how the day is scheduled
2. **No Overlapping**: System prevents double-booking occupied time slots
3. **Duration Awareness**: Instantly see how long each appointment takes
4. **Better Planning**: Easily identify available time blocks
5. **Professional Look**: Modern, clean design with smooth animations
6. **Mobile Responsive**: Works on all screen sizes

## Technical Details

### Models Used

- `Appointment`: Stores appointment data
- `Service`: Contains duration field (in minutes)
- `Dentist`: Has work schedule settings

### Key Files Modified

1. `app/Livewire/DayCalendar.php` - Backend logic
2. `resources/views/livewire/day-calendar.blade.php` - Frontend display

### Dependencies

- Laravel Livewire
- Carbon (for date/time handling)
- Flux UI components
- Tailwind CSS
