
Feature: Login
  In order to be logged in
  As a user
  I want to handle my request

  Scenario: Successful login with register
    Given "public.user" table is empty
    And "public.events" table is empty
    When I send a "POST" request to "/api/register" with JSON params:
      """
      {
        "uuid": "2b604f2f-fa01-451e-8e8b-2ed1fa7ea8b1",
        "email": "romain.delouard@gmail.com",
        "password": "riendutout"
      }
      """
    Then I should get 201 HTTP response status code

    When I send a "POST" request to "/api/auth_check" with JSON params:
      """
      {
        "_password": "riendutout",
        "_username": "romain.delouard@gmail.com"
      }
      """
    Then I should get 200 HTTP response status code

    When I send a "GET" request to "/api/users/romain.delouard%40gmail.com"
    Then I should get 200 HTTP response status code
