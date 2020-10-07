<?php

require __DIR__ . '/../models/recipe-model.php';

function browseRecipes(): void
{
    $recipes = getAllRecipes();

    require __DIR__ . '/../views/index.php';
}

function showRecipe(int $id): void
{
    $recipe = getRecipeById($id);

    require __DIR__ . '/../views/show.php';
}

function addRecipe(): void
{
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $recipe = array_map('trim', $_POST);
        $errors = validateRecipe($recipe);

        if (empty($errors)) {
            saveRecipe($recipe);
            header('Location: /');
        }
    }

    require __DIR__ . '/../views/form.php';
}

function validateRecipe(array $recipe): array
{
    if (empty($recipe['title'])) {
        $errors[] = 'The title is required';
    }
    if (empty($recipe['description'])) {
        $errors[] = 'The description is required';
    }
    if (!empty($recipe['title']) && strlen($recipe['title']) > 255) {
        $errors[] = 'The title should be less than 255 characters';
    }

    return $errors ?? [];
}
