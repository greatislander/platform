<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Page title').' ('.get_language_exonym('en').')')
                    ->disabled(),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Page title').' ('.get_language_exonym('fr').')')
                    ->disabled(),
                Forms\Components\MarkdownEditor::make('content.en')
                    ->disableToolbarButtons(['attachFiles'])
                    ->label(__('Content').' ('.get_language_exonym('en').')')
                    ->helperText(__('The following values will be expanded in the output: ":home", ":email", ":tos" and ":privacy_policy". You may wrap these values in "<>" to display the URL itself.'))
                    ->columnSpan(2),
                Forms\Components\MarkdownEditor::make('content.fr')
                    ->disableToolbarButtons(['attachFiles'])
                    ->label(__('Content').' ('.get_language_exonym('fr').')')
                    ->helperText(__('The following values will be expanded in the output: ":home", ":email", ":tos" and ":privacy_policy". You may wrap these values in "<>" to display the URL itself.'))
                    ->columnSpan(2),
            ]);
    }

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
