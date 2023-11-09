<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['title']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->url(fn (Page $record): string => localized_route('about.page', $record)),
        ];
    }
}
