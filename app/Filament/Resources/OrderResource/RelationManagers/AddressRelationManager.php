<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('firstName')
                ->required()->label('First Name')
                ->maxLength(255),
                Forms\Components\TextInput::make('lastName')
                ->required()->label('Last Name')
                ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                ->required()->label('Phone')->tel()
                ->maxLength(20),
                Forms\Components\TextInput::make('city')
                ->required()->label('City')
                ->maxLength(255),
                Forms\Components\TextInput::make('state')
                ->required()->label('State')
                ->maxLength(255),
                Forms\Components\TextInput::make('zipCode')
                ->required()->label('ZipCode')->numeric()
                ->maxLength(10),
                Forms\Components\TextArea::make('streetAddress')
                    ->required()->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('streetAddress')
            ->emptyStateHeading('No Address yet')
            ->emptyStateDescription('Once we register first Address, it will appear here.')
            ->emptyStateIcon('heroicon-o-bookmark')
          
            ->columns([
                Tables\Columns\TextColumn::make('fullName')->label('Full Name'),
                Tables\Columns\TextColumn::make('phone')->label('Phone'),
                Tables\Columns\TextColumn::make('city')->label('City'),
                Tables\Columns\TextColumn::make('state')->label('State'),
                Tables\Columns\TextColumn::make('streetAddress')->label('Street Address'),



            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
