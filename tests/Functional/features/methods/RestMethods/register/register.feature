Feature: Registration
  In order to be registered
  As a user
  I want to handle my request

  Scenario: register with valid credentials
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
    And the table "public.user" should contain the following rows:
      | uuid                                 | credentials_email         |
      | 2b604f2f-fa01-451e-8e8b-2ed1fa7ea8b1 | romain.delouard@gmail.com |