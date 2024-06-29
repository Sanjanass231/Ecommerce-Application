<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables;
use Illuminate\Support\Number;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Group::make()->schema([
             Section::make('Order Information')->schema([
                Select::make('user_id')
                -> label('Customer')
                ->relationship('user','name')
                ->searchable()
                ->preload()
                ->required(),
                Select::make('paymentMethod')
                ->label('Payment Method')
                ->options([
                    'stripe'=>'Stripe',
                    'COD'=>'Cash On Deleivery'
                ])
                ->preload()
                ->required(),
                Select::make('paymentStatus')
                ->label('Payment Status')
                ->options([
                    'pending'=>'Pending',
                    'paid'=>'Paid',
                    'failed'=>'Failed'
                ])->default('pending')
                ->preload()
                ->required(),
                ToggleButtons::make('status')->inline()->default('new')->required()
                ->label('Status')
                ->options(['new'=>'New',
                'processing'=>'Processing',
                'shipped'=>'Shipped',
                'delivered'=>'Delivered',
                'canceled'=>'Canceled',
                ])
                ->colors([
                    'new' => 'info',
                    'processing' => 'warning',
                    'shipped' => 'success',
                    'delivered' => 'success',
                    'canceled' => 'danger',
                ])
                ->icons([
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-badge',
                    'canceled' => 'heroicon-m-x-circle',
                ]),
                Select::make('currency')
                ->label('Currency')
                ->options([
                    'inr'=>'INR',
                    'usd'=>'USD',
                    'eur'=>'EUR',
                     'gbp'=>'GBP'  
                ])->default('inr')
                ->required(),
                Select::make('shippingMethod')
                ->label('Shipping method')
                ->options([
                    'fedex'=>'Fedex',
                    'ups'=>'UPS',
                    'dhl'=>'DHL',
                     'usps'=>'USPS'  
                ]),
                TextArea::make('notes')->columnSpanFull()
              ])->columns(2),
              Section::make('Order Items')->schema([
             Repeater::make('items')->relationship()->schema([
               Select::make('product_id')
               ->relationship('product','name')
               ->searchable()->preload()->required()->distinct()->disableOptionsWhenSelectedInSiblingRepeaterItems()->columnSpan(4)
               ->reactive()
               ->afterStateUpdated(fn (?string $state, Set $set)=>$set('unitAmount',Product::find($state)->price ?? 0 ))  
               ->afterStateUpdated(fn (?string $state, Set $set)=>$set('totalAmount',Product::find($state)->price ?? 0 )),
             TextInput::make('quatity')->numeric()->required()->default(1)->minValue(1)->columnSpan(2)
             ->reactive()->afterStateUpdated(fn (?string $state, Set $set, Get $get)=>$set('totalAmount',$state * $get('unitAmount') )) ,
            TextInput::make('unitAmount')->numeric()->dehydrated()->required()->readOnly()->columnSpan(3),
            TextInput::make('totalAmount')->numeric()->required()->columnSpan(3)
            ->dehydrated()
             ])->columns(12),
            Placeholder::make('grandTotal')
             ->label('Grand Total')
             ->content(function(Get $get, Set $set){
                $total = 0;
                if(!$repeaters = $get('items')){
                    return $total;
                }
                foreach($repeaters as $key => $repeater ){
                    $total+= $get("items.{$key}.totalAmount");
                }
                $set('grandTotal',$total);
                return Number::currency($total,'INR');
                }),
                Hidden::make('grandTotal')->default(0)
              ])
            ])->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->emptyStateHeading('No Orders yet')
        ->emptyStateDescription('Once we place first Order, it will appear here.')
        ->emptyStateIcon('heroicon-o-bookmark')         
            ->columns([
                TextColumn::make('user.name')->sortable()->searchable()->label('User'),
                TextColumn::make('grandTotal')->sortable()->numeric()->label('Grand Total')->money('INR'),
                TextColumn::make('paymentMethod')->sortable()->searchable()->label('Payment Method'),
                TextColumn::make('paymentStatus')->sortable()->searchable()->label('Payment Status'),
                SelectColumn::make('status')->options(['new'=>'New',
                'processing'=>'Processing',
                'shipped'=>'Shipped',
                'delivered'=>'Delivered',
                'canceled'=>'Canceled',
                ])->label('Status')->sortable()->searchable(),
                TextColumn::make('currency')->sortable()->searchable()->label('Currency'),
                TextColumn::make('shippingMethod')->sortable()->searchable()->label('Shipping Method'),
                TextColumn::make('created_at')->sortable()->label('Created At')->dateTime()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('updated_at')->sortable()->label('Created At')->dateTime()->toggleable(isToggledHiddenByDefault:true),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string{
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
