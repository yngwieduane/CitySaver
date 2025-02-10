<?php

namespace App\Filament\Citysaver\Resources;

use App\Filament\Citysaver\Resources\CountryResource\Pages;
use App\Filament\Citysaver\Resources\CountryResource\RelationManagers;
use App\Filament\Citysaver\Resources\CountryResource\RelationManagers\CitiesRelationManager;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Locations';
    
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('slug', Str::slug($state));
                            })
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'The name has already been registered.',
                            ]),
    
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->readonly()
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\TextInput::make('short_name')
                            ->required(),
                        
                        Forms\Components\TextInput::make('population'),

                        Forms\Components\TextInput::make('wiki_url')
                            ->columnSpanFull(),
    
                        Forms\Components\MarkdownEditor::make('description')
                            ->maxLength(255)->columnSpanFull(),
                    ])->columns(columns:2)
                ]),
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_visible')
                            ->label('Visibility')
                            ->helperText('Enable or disable country visibility'),
                            
                        Forms\Components\FileUpload::make('image')
                            ->image(),
                            
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('short_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->boolean()
                    ->sortable()
                    ->label('Visibility'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CitiesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
