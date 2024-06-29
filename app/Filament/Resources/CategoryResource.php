<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    public static function form(Form $form): Form
    {
        return $form
        // 'name','slug','image','isActive'
            ->schema([
                Section::make('Category')
                ->description('Enter Category Details')
                ->schema([
                    TextInput::make('name')->label('Name')->required()
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ,
                    TextInput::make('slug')->label('Slug')->maxlength(255)->unique(ignoreRecord:true)->readOnly(),                     
                    FileUpload::make('image')->required()->label('Image'),
                    Toggle::make('isActive')->label('Is Active'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->emptyStateHeading('No Category yet')
        ->emptyStateDescription('Once we create first category, it will appear here.')
        ->emptyStateIcon('heroicon-o-bookmark')
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('name')->label('Name')->searchable(),
                ImageColumn::make('image')->label('Image')->square(),
                IconColumn::make('isActive')->label('Is Active')->boolean()
                ->icon(fn (string $state): string => match ($state) {
                    '0' => 'heroicon-o-face-frown',
                    '1' => 'heroicon-o-check-circle',
                })
                ->color(fn (string $state): string => match ($state) {
                    '0' => 'warning',
                    '1' => 'success',
                }),
                TextColumn::make('created_at')->label('Created At')->date()->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
           ;
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
