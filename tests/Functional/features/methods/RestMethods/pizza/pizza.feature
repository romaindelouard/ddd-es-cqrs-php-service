Feature: Get Pizzas
  In order to aggregate pizzas
  As the system
  I need to be able to get several pizzas at once

  Scenario: Get several pizzas
    When I send a "POST" request to "/api/auth_check" with JSON params:
      """
      {
        "_password": "riendutout",
        "_username": "romain.delouard@gmail.com"
      }
      """
    Then I should get 200 HTTP response status code

    Given the table "pizza" contains the following rows:
      | uuid                                 | name      |
      | 70c5793d-2ec4-4a68-be05-5f42ae3afce8 | 4 cheeses |
      | 9c41ab61-f7b1-4157-91f9-c8989cc2ca71 | parme     |
    When I send a "GET" request to "/api/pizzas"
    Then I should get 200 HTTP response status code
