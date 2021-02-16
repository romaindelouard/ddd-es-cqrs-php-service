Feature: Test the db connection
  As a dev
  In order to work on this project
  I want the database to be available

  Scenario: I insert and select data from the pizza table
    Given the table "pizza" contains the following rows:
      | uuid                                 | name      |
      | 70c5793d-2ec4-4a68-be05-5f42ae3afce8 | 4 cheeses |
      | 9c41ab61-f7b1-4157-91f9-c8989cc2ca71 | parme     |
    Then the table "pizza" should contain the following rows:
      | uuid                                 | name      |
      | 70c5793d-2ec4-4a68-be05-5f42ae3afce8 | 4 cheeses |
      | 9c41ab61-f7b1-4157-91f9-c8989cc2ca71 | parme     |
