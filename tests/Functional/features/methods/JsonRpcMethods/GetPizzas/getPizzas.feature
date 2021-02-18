Feature: Get Pizzas
  In order to aggregate pizzas
  As the system
  I need to be able to get several pizzas at once

  Scenario: I insert and select data from the pizza table
    Given the table "pizza" contains the following rows:
      | uuid                                 | name      |
      | 70c5793d-2ec4-4a68-be05-5f42ae3afce8 | 4 cheeses |
      | 9c41ab61-f7b1-4157-91f9-c8989cc2ca71 | parme     |
    When I call JSON-RPC method "getPizzas" with params:
      """
      {
        "page": 1,
        "limit": 10
      }
      """
    Then I should get result containing "meta" property containing all of the following:
      """
      {
        "size": 50,
        "page": 1,
        "total": 2
      }
      """