<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Filament\Resources\OrderResource;
use Filament\Tables\Table;
use App\Models\Order;
use Filament\Tables\Actions\Action;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan= 'full'; 
    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOPtion(5)
            ->defaultSort('created_at','desc')
            ->columns([
                    Tables\Columns\TextColumn::make('grandTotal')->sortable()->label('Grand Total')->money('INR'),
                    Tables\Columns\TextColumn::make('id')->label('ID')->searchable(),
                    Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
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
            ->actions([
                Action::make('View Order')->url(fn(Order $record): string =>
                OrderResource::getUrl('view',['record' => $record]))
            ])
            ;
    }
}
