<?php

namespace App\Filament\Citysaver\Resources;

use App\Filament\Citysaver\Resources\SubCategoryResource\Pages;
use App\Filament\Citysaver\Resources\SubCategoryResource\RelationManagers;
use App\Models\SubCategory;
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

class SubCategoryResource extends Resource
{
    protected static ?string $model = SubCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->options(Category::pluck('name','id'))
                        ->live()
                        ->required(),
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
                
                Tables\Columns\TextColumn::make('category.name')->searchable()->sortable(),

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
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubCategories::route('/'),
            'create' => Pages\CreateSubCategory::route('/create'),
            'edit' => Pages\EditSubCategory::route('/{record}/edit'),
        ];
    }
}
