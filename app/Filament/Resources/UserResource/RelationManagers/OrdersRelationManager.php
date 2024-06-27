<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Filament\Resources\OrderResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Order;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('grandTotal')->sortable()->label('Grand Total')->money('INR'),
                Tables\Columns\TextColumn::make('id')->label('ID')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Order Status')->badge()->color(fn(string $state):string => match($state){
                    'new'=>'info',
                    'processing'=>'warning',
                    'shipped'=>'success',
                    'delivered'=>'success',
                    'canceled'=>'danger'
                })
                ->icons([
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-badge',
                    'canceled' => 'heroicon-m-x-circle',
                ])->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod')->sortable()->searchable()->label('Payment Method'),
                Tables\Columns\TextColumn::make('paymentStatus')->sortable()->label('Payment Status')->searchable()->badge(),
                Tables\Columns\TextColumn::make('created_at')->sortable()->label('Order Date')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
              Action::make('view order')->url(fn(Order $record):string => OrderResource::getUrl('view',['record'=>$record]))->color('info')->icon('heroicon-o-eye'),
              DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
