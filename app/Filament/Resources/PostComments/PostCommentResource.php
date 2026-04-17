<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostComments;

use App\Filament\Resources\PostComments\Pages\EditPostComment;
use App\Filament\Resources\PostComments\Pages\ListPostComments;
use App\Filament\Resources\PostComments\Schemas\PostCommentForm;
use App\Filament\Resources\PostComments\Tables\PostCommentsTable;
use App\Models\PostComment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PostCommentResource extends Resource
{
    protected static ?string $model = PostComment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Komentar Berita';

    protected static ?string $modelLabel = 'Komentar Berita';

    protected static ?string $pluralModelLabel = 'Komentar Berita';

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return PostCommentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostCommentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPostComments::route('/'),
            'edit' => EditPostComment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('post')
            ->latest();
    }
}
