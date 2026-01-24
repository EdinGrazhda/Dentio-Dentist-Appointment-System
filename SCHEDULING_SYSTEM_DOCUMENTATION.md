# Dynamic Service Duration Scheduling System

## Overview
This system implements intelligent appointment scheduling that automatically adjusts time slot availability and calendar visualization based on service duration.

## Data Model

### 1. Services Table
```sql
- id: Primary key
- service_name: VARCHAR (e.g., "Teeth Whitening", "Root Canal")
- description: TEXT
- price: DECIMAL
- duration: INTEGER (in minutes: 20, 30, 60, 90, etc.)
```

**Examples:**
- Teeth Whitening: 20 minutes
- Regular Check-up: 30 minutes  
- Dental Filling: 60 minutes
- Root Canal: 90 minutes

### 2. Appointments Table
```sql
- id: Primary key
- patient_name: VARCHAR
- email: VARCHAR
- phone: VARCHAR
- dentist_id: Foreign key to dentists
- service_id: Foreign key to services
- appointment_date: DATETIME (start time)
- status: ENUM (pending, confirmed, completed, cancelled)
```

### 3. Dentists Table
```sql
- id: Primary key
- name: VARCHAR
- specialization: VARCHAR
- work_start_time: TIME (e.g., 08:00:00)
- work_end_time: TIME (e.g., 16:00:00)
- slot_duration: INTEGER (in minutes: 15, 30, etc.)
```

## Scheduling Logic

### AppointmentAvailabilityService

#### Core Functions

**1. Slot Availability Check**
```php
public function getAvailableSlots(int $dentistId, int $serviceId, string $date): array
```
- Loads dentist's working hours
- Gets service duration
- Generates time slots based on dentist's slot_duration setting
- For each slot, checks if service can fit completely before working hours end
- Validates no overlapping appointments exist
- Returns only slots where the ENTIRE service duration fits

**Example:**
```
Dentist working hours: 08:00 - 16:00
Slot interval: 30 minutes
Service: Root Canal (90 minutes)

Available slots:
✅ 08:00 - 09:30 (fits)
✅ 08:30 - 10:00 (fits)
✅ 09:00 - 10:30 (fits)
...
✅ 14:30 - 16:00 (fits perfectly)
❌ 15:00 - 16:30 (ends after 16:00, rejected)
```

**2. Overlap Detection**
```php
protected function slotsOverlap(Carbon $startA, Carbon $endA, Carbon $startB, Carbon $endB): bool
```
- Checks if two time ranges overlap
- Algorithm: `startA < endB AND endA > startB`
- Used to prevent double-booking

**3. Conflict Detection**
```php
public function findConflict(int $dentistId, int $serviceId, DateTimeInterface $appointmentDate): ?Appointment
```
- Calculates end time: `start_time + service_duration`
- Fetches all confirmed/pending appointments for the dentist
- Checks each existing appointment for overlap
- Returns first conflicting appointment or null

**4. Dynamic Duration Calculation**
```php
protected function calculateEnd(Carbon $start, ?DentalService $service): Carbon
{
    $duration = max(1, (int) ($service?->duration ?? 60));
    return $start->copy()->addMinutes($duration);
}
```

## Calendar UI Implementation

### Day Calendar View (`CalendarView.php`)

**Features:**
1. **Time Slot Display** - Shows hourly slots (8:00 AM, 8:30 AM, etc.)
2. **Dynamic Appointment Height** - Appointments visually span their duration
3. **Color-Coded Status**:
   - 🟡 Pending: Yellow
   - 🟢 Confirmed: Green
   - 🔵 Completed: Blue
   - 🔴 Cancelled: Red

**Visual Calculation:**
```php
$durationMinutes = $appointment['duration_minutes'];
$slotsSpanned = ceil($durationMinutes / $slotDuration);
$heightInPixels = ($slotsSpanned * 64) - 4; // 64px per slot
```

**Example:**
- 30-minute appointment with 30-minute slots: 1 slot tall (60px)
- 60-minute appointment with 30-minute slots: 2 slots tall (124px)
- 90-minute appointment with 30-minute slots: 3 slots tall (188px)

### Booking Modal (`BookAppointmentModal.php`)

**Step 1: Service Selection**
- Shows service name, duration, and price
- Example: "Root Canal - 1h 30min - $250.00"
- When service changes, automatically reloads available slots

**Step 2: Time Slot Selection**
- Only displays slots where the ENTIRE service duration fits
- Shows duration indicator: "This service takes 90 minutes"
- Grays out unavailable slots
- Updates in real-time when service changes

**Step 3: Email Verification**
- 4-character code sent to customer
- Validates before booking
- Creates appointment with CONFIRMED status

## Preventing Overlapping Appointments

### Multi-Layer Protection

**1. Frontend Validation**
- Only show available slots in UI
- Disable already-booked times

**2. Backend Validation** (Primary Protection)
```php
$this->availabilityService->ensureSlotIsAvailable(
    $dentistId,
    $serviceId,
    $appointmentDate
);
```
Throws `ValidationException` if conflict detected with detailed message:
> "Dr. Smith already has Root Canal between Jan 20, 2026 14:00 and 15:30."

**3. Database Constraints**
- `whereIn('status', ['pending', 'confirmed'])` - Only check active appointments
- Transaction safety when creating appointments

## Usage Examples

### Example 1: Booking a 20-Minute Teeth Whitening
```
1. Customer selects "Teeth Whitening (20 min)"
2. System calculates: 08:00 + 20 min = 08:20
3. Checks for conflicts between 08:00 - 08:20
4. If available, shows slot
5. On booking, blocks 08:00 - 08:20
```

### Example 2: Booking a 90-Minute Root Canal
```
1. Customer selects "Root Canal (1h 30min)"
2. Dentist works 08:00 - 16:00 with 30-min slots
3. System shows: 08:00, 08:30, 09:00, ..., 14:30
4. Does NOT show: 15:00 (would end at 16:30, past closing)
5. Customer books 14:30
6. System blocks 14:30 - 16:00 (3 slots)
7. Calendar displays appointment spanning 3 time slots
```

### Example 3: Conflict Prevention
```
Existing: 10:00 - 11:00 (Dental Filling, 60 min)
New Request: 10:30 - 11:00 (Check-up, 30 min)

Check: Does [10:30, 11:00] overlap [10:00, 11:00]?
- 10:30 < 11:00 ✅
- 11:00 > 10:00 ✅
Result: CONFLICT - Request rejected
```

## Configuration Options

### Dentist Settings
```php
// From Settings/WorkingHours.php or calendar settings
work_start_time: '08:00'
work_end_time: '16:00'
slot_duration: 30 // minutes between slots
```

### Service Settings
```php
// From Admin > Services
duration: 90 // Total service time in minutes
```

## Testing Scenarios

### Test 1: Service Duration Validation
- Create services with durations: 15, 30, 45, 60, 90, 120 minutes
- Verify each shows correct duration in dropdowns

### Test 2: Slot Generation
- Set working hours 08:00 - 12:00
- Set slot duration to 30 minutes
- Book 90-minute service
- Verify only 08:00, 08:30, 09:00, 09:30, 10:00, 10:30 are shown
- Verify 11:00 and 11:30 are NOT shown (would exceed 12:00)

### Test 3: Overlap Prevention
- Book 09:00 - 10:30 (90 min)
- Attempt to book 09:30 - 10:00 (30 min)
- Verify error: "Dentist already has appointment..."

### Test 4: Visual Spanning
- Book multiple appointments with different durations
- 08:00: 30-minute check-up (should span 1 slot)
- 09:00: 60-minute filling (should span 2 slots)
- 11:00: 90-minute root canal (should span 3 slots)
- Verify calendar displays correct heights

## Benefits

✅ **Accurate Time Management** - No more manual calculations
✅ **Automatic Conflict Prevention** - System prevents double-booking
✅ **Visual Clarity** - See appointment durations at a glance
✅ **Flexible Configuration** - Adjust working hours and slot durations per dentist
✅ **Better UX** - Customers see only truly available slots
✅ **Service-Aware** - Different treatments automatically get correct time

## Future Enhancements

1. **Break Times** - Add lunch breaks, buffer times between appointments
2. **Multi-Day Appointments** - Support treatments spanning multiple visits
3. **Resource Booking** - Track equipment availability (dental chairs, X-ray machines)
4. **Smart Scheduling** - AI-suggested optimal appointment times
5. **Cancellation Buffer** - Auto-release slots if cancelled early enough
6. **Wait List** - Notify customers when slots become available

## Summary

This system provides a complete solution for dynamic duration-based scheduling:
- ✅ Data model supports flexible service durations
- ✅ Logic automatically validates slot availability
- ✅ Calendar UI visually represents appointment lengths
- ✅ Booking flow prevents impossible reservations
- ✅ Multi-layer conflict prevention ensures data integrity

The key innovation is that availability checking considers BOTH service duration AND existing appointments, ensuring every booking is guaranteed to fit within working hours and not overlap with other appointments.
