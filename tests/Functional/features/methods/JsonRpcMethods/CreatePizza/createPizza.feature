Feature: Create a pizza
  In order to keep my database up to date
  As the system
  I need to be able to create a pizza

  Scenario: Creating pizza
    When I call JSON-RPC method "createPizza" with params:
      """
      {
        "name": "4 pizzas",
        "description": "super cheeses"
      }
      """
    Then I should get result containing "pizza" property containing all of the following:
      """
      {
        "name": "4 pizzas",
        "description": "super cheeses"
      }
      """
    And I expect event collector listener to collect 1 event type "Romaind\PizzaStore\Domain\Event\Pizza\PizzaWasCreated"
