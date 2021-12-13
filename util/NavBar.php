<?php
include_once("../config.php");
include_once('util/NavBar.php');
include_once("Student.php");
require_once rootDirectory() . '/vendor/autoload.php';


class NavBar
{
    protected string $userType;
    protected string $currPage;

    /**
     * @param string $userType
     * @throws Exception
     */
    public function __construct(string $userType)
    {
        $this->userType = $userType;
        $this->currPage = '';
    }

    public function draw(): string
    {


        $EENGINE = new Mustache_Engine(['loader' => new Mustache_Loader_FilesystemLoader(rootDirectory() . '/templates'),]);
        if (0 == strcmp($this->userType, 'administration')) {
            $nav = $EENGINE->render('navbar', [
                'title' => 'Main Page',
                'links' => [
                    ['href' => '/administration', 'title' => 'Administration', 'id' => 'selected'],
                    ['href' => '/lectures', 'title' => 'Lectures'],
                    //['href' => '/reservations', 'title' => 'Reservations'],
                    ['href' => '/events', 'title' => 'Events'],
                    ['href' => '/closecontact', 'title' => 'Close Contact'],
                    ['href' => '/profile', 'title' => 'Profile']
                ]
            ]);
        } elseif (0 == strcmp($this->userType, 'academic')) {
            $nav = $EENGINE->render('navbar', [
                'title' => 'Main Page',
                'links' => [
                    ['href' => '/administration', 'title' => 'Administration'],
                    ['href' => '/lectures', 'title' => 'Lectures'],
                    ['href' => '/reservations', 'title' => 'Reservations'],
                    ['href' => '/events', 'title' => 'Events'],
                    ['href' => '/closecontact', 'title' => 'Close Contact'],
                    ['href' => '/profile', 'title' => 'Profile']
                ]
            ]);
        } elseif (0 == strcmp($this->userType, 'sport')) {
            $nav = $EENGINE->render('navbar', [
                'title' => 'Main Page',
                'links' => [
                    ['href' => '/administration', 'title' => 'Administration'],
                    ['href' => '/lectures', 'title' => 'Lectures'],
                    ['href' => '/reservations', 'title' => 'Reservations'],
                    ['href' => '/events', 'title' => 'Events'],
                    ['href' => '/closecontact', 'title' => 'Close Contact'],
                    ['href' => '/profile', 'title' => 'Profile']
                ]
            ]);
        } elseif (0 == strcmp($this->userType, Student::TABLE_NAME)) {
            $nav = $EENGINE->render('navbar', [
                'title' => 'Main Page',
                'links' => [
                    ['href' => '/reservations', 'title' => 'Reservations'],
                    ['href' => '/events', 'title' => 'Events'],
                    ['href' => '/closecontact', 'title' => 'Close Contact'],
                    ['href' => '/profile', 'title' => 'Profile']
                ]
            ]);
        } else {
            $nav = $EENGINE->render('navbar', [
                'title' => 'Main Page']);
        }
        return $nav;
    }

}