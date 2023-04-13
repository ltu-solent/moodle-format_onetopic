@format @format_onetopic @sol @javascript
Feature: Summative assignments cannot be deleted or have its name changed
  In order to protect Summative assignments from accidental editing or deletion
  As a teacher
  I cannot edit the assignment name or delete the activity

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email            |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | format   | coursedisplay | numsections |
      | Course 1 | C1        | onetopic | 0             | 5           |
    And the following "activities" exist:
      | activity   | name                   | intro                         | course | idnumber    | section |
      | assign     | Summative assignment   | Summative assignment desc     | C1     | assign1     | 1       |
      | assign     | Formative assignment   | Formative assignment desc     | C1     |             | 1       |
      | book       | Test book name         | Test book description         | C1     | book1       | 1       |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on

  Scenario: Summative assignment has no inline name editing
    When I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Summative assignment"
    And "[data-value='Summative assignment']" "css_element" should not exist
    And I should see "Formative assignment"
    And I set the field "Edit title" in the "Formative assignment" "activity" to "Good news assignment"
    And I should see "Good news assignment"
    And "[data-value='Good news assignment']" "css_element" should exist

  Scenario: Summative assignment has no delete action
    When I click on "Topic 1" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    And I open the action menu in "[data-activityname='Summative assignment']" "css_element"
    Then I should see "Edit settings" in the "[data-activityname='Summative assignment'] .cm_action_menu" "css_element"
    And I should not see "Delete" in the "[data-activityname='Summative assignment'] .cm_action_menu" "css_element"
    And I should not see "Hide" in the "[data-activityname='Summative assignment'] .cm_action_menu" "css_element"
    And I close "Summative assignment" actions menu
    When I open the action menu in "[data-activityname='Formative assignment']" "css_element"
    Then I should see "Edit settings" in the "[data-activityname='Formative assignment'] .cm_action_menu" "css_element"
    And I should see "Delete" in the "[data-activityname='Formative assignment'] .cm_action_menu" "css_element"
    And I should see "Hide" in the "[data-activityname='Formative assignment'] .cm_action_menu" "css_element"
