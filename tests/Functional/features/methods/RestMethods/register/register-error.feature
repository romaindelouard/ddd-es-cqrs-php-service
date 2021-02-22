Feature: Registration Error
  In order to avoid ugly data in database and lacks of synchronization
  As a user
  I want to validate online users input

  Background: truncate user and events tables
    Given "public.user" table is empty
    And "public.events" table is empty

  Scenario: Trying to register with invalid password length
    When I send a "POST" request to "/api/register" with JSON params:
      """
      {
        "uuid": "2b604f2f-fa01-451e-8e8b-2ed1fa7ea8b1",
        "email": "toto@domain.com",
        "password": "12"
      }
      """
    Then I should get 400 HTTP response status code
    And I should get a HTTP response with error message:
      """
      {
        "title": "Assert.InvalidArgumentException",
        "detail": "Min 6 characters password",
        "code": 18
      }
      """
    And I expect event collector listener to collect 0 event

  Scenario: Trying to register with invalid uuid type
    When I send a "POST" request to "/api/register" with JSON params:
      """
      {
        "uuid": "bad identifier",
        "email": "romain.delouard@gmail.com",
        "password": "12"
      }
      """
    Then I should get 400 HTTP response status code
    And I should get a HTTP response with error message:
      """
      {
        "title": "Ramsey.Uuid.Exception.InvalidUuidStringException",
        "detail": "Invalid UUID string: bad identifier",
        "code": 0
      }
      """
    And I expect event collector listener to collect 0 event

  Scenario: Trying to register with email uuid type
    When I send a "POST" request to "/api/register" with JSON params:
      """
      {
        "uuid": "2b604f2f-fa01-451e-8e8b-2ed1fa7ea8b1",
        "email": "toto@domain.com@invalid",
        "password": "mypassword"
      }
      """
    Then I should get 400 HTTP response status code
    And I should get a HTTP response with error message:
      """
      {
        "title": "Assert.InvalidArgumentException",
        "detail": "Not a valid email",
        "code": 201
      }
      """
    And I expect event collector listener to collect 0 event

  Scenario: Trying to register without email
    When I send a "POST" request to "/api/register" with JSON params:
      """
      {
        "uuid": "2b604f2f-fa01-451e-8e8b-2ed1fa7ea8b1",
        "password": "mypassword"
      }
      """
    Then I should get 400 HTTP response status code
    And I should get a HTTP response with error message:
      """
      {
        "title": "Assert.InvalidArgumentException",
        "detail": "Email can\\'t be null",
        "code": 15
      }
      """
    And I expect event collector listener to collect 0 event

  Scenario: Trying to register without password
    When I send a "POST" request to "/api/register" with JSON params:
      """
      {
        "uuid": "2b604f2f-fa01-451e-8e8b-2ed1fa7ea8b1",
        "email": "oui@domain.com"
      }
      """
    Then I should get 400 HTTP response status code
    And I should get a HTTP response with error message:
      """
      {
        "title": "Assert.InvalidArgumentException",
        "detail": "Password can\\'t be null",
        "code": 15
      }
      """
    And I expect event collector listener to collect 0 event

  Scenario: Trying to register without uuid
    When I send a "POST" request to "/api/register" with JSON params:
      """
      {
        "password": "mypassword",
        "email": "oui@domain.com"
      }
      """
    Then I should get 400 HTTP response status code
    And I should get a HTTP response with error message:
      """
      {
        "title": "Assert.InvalidArgumentException",
        "detail": "Uuid can\\'t be null",
        "code": 15
      }
      """
    And I expect event collector listener to collect 0 event
