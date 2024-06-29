<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Group;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
       ->schema([
                Section::make('Product Information')
                   ->schema([
                    TextInput::make('name')->label('Name')->required()
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')->label('Slug')->maxlength(255)->unique(ignoreRecord:true)->readOnly(), 
                     MarkdownEditor::make('description')->columnSpanFull()      
                   ])->columns(2),
               Section::make('Images')
               ->schema([
                FileUpload::make('images')->required()->label('Images')->multiple()->reorderable(),
            ]),
        ])->columnSpan(2),
        Group::make()->schema([
            Section::make('price')->schema([
                TextInput::make('price')->numeric()->required()->prefix('INR')->label('Price')
            ]),
            Section::make('Associations')->schema([
               Select::make('category_id')->required()->searchable()->preload()->relationship('category','name')->label('Category'),
               Select::make('brand_id')->required()->searchable()->preload()->relationship('brand','name')->label('Brand'),
            ])->columns(2),
            Section::make('Status')->schema([
                Toggle::make('inStock')->label('In Stock')->default('true'),
                Toggle::make('isActive')->label('Is Active')->default('true'),
                Toggle::make('isFeatured')->label('Is Featured')->required(),
                Toggle::make('onSale')->label('On Sale')->required(),
            ]),
        ])->columnSpan(1)
      ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->emptyStateHeading('No Product yet')
        ->emptyStateDescription('Once we create first Product, it will appear here.')
        ->emptyStateIcon('heroicon-o-bookmark')
            ->columns([
                TextColumn::make('name')->label('Name')->searchable(),
                TextColumn::make('category.name')->label('Category')->searchable(),
                TextColumn::make('brand.name')->label('Brand')->searchable(),
                TextColumn::make('price')->label('Price')->sortable()->money('INR'),
                IconColumn::make('isFeatured')->label('Is Featured')->boolean(),
                IconColumn::make('isActive')->label('Is Active')->boolean(),
                IconColumn::make('inStock')->label('In Stock')->boolean(),  
                IconColumn::make('onSale')->label('On Sale')->boolean(), 
                TextColumn::make('created_at')->label('Create At')->sortable()->dateTime()
                ->toggleable(isToggledHiddenByDefault:true), 
            ])
            ->filters([
                SelectFilter::make('category')
                 ->label('Category')
                  ->relationship('category', 'name'),
                SelectFilter::make('brand')
                 ->label('Brand')
                  ->relationship('brand', 'name')
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            // 'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
