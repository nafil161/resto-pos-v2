<?php

/*
|--------------------------------------------------------------------------
| App Registry
|--------------------------------------------------------------------------
| Each app registered here gets a sidebar, entry route, and can be
| added to a user's dashboard. The 'sidebar' array defines the menu
| items shown when the user is inside that app.
|
| Route naming convention: all routes for an app MUST be prefixed with
| the app's slug (e.g. notes.index, notes.create, reminders.index).
|
*/

return [

    'notes' => [
        'name'        => 'Notes',
        'slug'        => 'notes',
        'description' => 'Capture and organise your thoughts, ideas, and reminders.',
        'icon'        => 'tabler-notes',
        'icon_bg'     => 'bg-label-primary',
        'is_free'     => true,
        'entry_route' => 'notes.index',
        'sidebar'     => [
            [
                'label' => 'All Notes',
                'route' => 'notes.index',
                'icon'  => 'tabler-notes',
            ],
            [
                'label' => 'Add Note',
                'route' => 'notes.create',
                'icon'  => 'tabler-circle-plus',
            ],
        ],
    ],

    'reminders' => [
        'name'        => 'Reminder',
        'slug'        => 'reminders',
        'description' => 'Stay on top of important tasks and never miss a deadline.',
        'icon'        => 'tabler-bell',
        'icon_bg'     => 'bg-label-warning',
        'is_free'     => true,
        'entry_route' => 'reminders.index',
        'sidebar'     => [
            [
                'label' => 'All Reminders',
                'route' => 'reminders.index',
                'icon'  => 'tabler-bell',
            ],
            [
                'label' => 'Add Reminder',
                'route' => 'reminders.create',
                'icon'  => 'tabler-bell-plus',
            ],
        ],
    ],

    'todos' => [
        'name'        => 'Todo',
        'slug'        => 'todos',
        'description' => 'Track tasks and mark them complete when done.',
        'icon'        => 'tabler-list-check',
        'icon_bg'     => 'bg-label-info',
        'is_free'     => true,
        'entry_route' => 'todos.index',
        'sidebar'     => [
            [
                'label' => 'All Todos',
                'route' => 'todos.index',
                'icon'  => 'tabler-list',
            ],
            [
                'label' => 'Add Todo',
                'route' => 'todos.create',
                'icon'  => 'tabler-circle-plus',
            ],
        ],
    ],

];
