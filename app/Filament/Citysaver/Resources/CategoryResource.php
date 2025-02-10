<?php

namespace App\Filament\Citysaver\Resources;

use App\Filament\Citysaver\Resources\CategoryResource\Api\Transformers\CategoryTransformer;
use App\Filament\Citysaver\Resources\CategoryResource\Pages;
use App\Filament\Citysaver\Resources\CategoryResource\RelationManagers;
use App\Filament\Citysaver\Resources\SubcategoryResource\RelationManagers\SubcategoryRelationManager;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
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
                        ->readonly(),
                    Forms\Components\MarkdownEditor::make('description'),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('description')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                ->date()
                ->searchable()
                ->sortable(),

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
            SubcategoryRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getApiTransformer()
    {
        return CategoryTransformer::class;
    }
    
}
