
Feature: Create a event search
  In order to be able to search in the event store
  As service
  I need to create a search event index in elasticsearch when I launch a command

  Scenario: Successfully creating event search
    When I run "app:create-event-search" command
    Then Command should be successfully executed
    And command output should contain "Event search was created!"
