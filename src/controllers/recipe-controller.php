<?php

require __DIR__ . '/../models/recipe-model.php';

function browseRecipes(): void
{
    // Fetching all recipes
    $recipes = getAllRecipes();

    // Generate the web page
    require __DIR__ . '/../views/index.php';
}

function showRecipe(int $id): void
{
    // Input parameter validation (integer >0)
    $id = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if (false === $id || null === $id) {
        header("Location: /");
        exit("Wrong input parameter");
    }

    // Fetching a recipe
    $recipe = getRecipeById($id);

    // Result check
    if (!isset($recipe['title']) || !isset($recipe['description'])) {
        header("Location: /");
        exit("Recipe not found");
    }

    // Generate the web page
    require __DIR__ . '/../views/show.php';
}

function addRecipe(): void
{
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        $recipe = array_map('trim', $_POST);

        // Validate data
        $errors = validateRecipe($recipe);

        // Save the recipe
        if (empty($errors)) {
            saveRecipe($recipe);
            header('Location: /');
        }
    }

    // Generate the web page
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
