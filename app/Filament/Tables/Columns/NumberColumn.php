<?php

namespace App\Filament\Tables\Columns;

use stdClass;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;

class NumberColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('No');

        $this->state(
            static function (HasTable $livewire, stdClass $rowLoop): string {
                return (string) (
                    $rowLoop->iteration +
                    ((int) $livewire->getTableRecordsPerPage() * ((int) $livewire->getTablePage() - 1))
                );
            }
        );
    }

    public static function make(string $name): static
    {
        return parent::make($name);
    }
}
