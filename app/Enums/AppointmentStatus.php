<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELED = 'canceled';

    /**
     * Get the label for the status
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CANCELED => 'Canceled',
        };
    }

    /**
     * Get the color for the status (for badges)
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => '#FFA500',    // Orange
            self::CONFIRMED => '#4988C4',  // Primary blue
            self::CANCELED => '#DC2626',   // Red
        };
    }

    /**
     * Get the background color for the status (for badges)
     */
    public function backgroundColor(): string
    {
        return match ($this) {
            self::PENDING => '#FFF3E0',    // Light orange
            self::CONFIRMED => '#E3F2FD',  // Light blue
            self::CANCELED => '#FEE2E2',   // Light red
        };
    }

    /**
     * Get all status values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all statuses as options for select/dropdown
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }
}
