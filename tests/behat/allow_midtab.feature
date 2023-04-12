@format @format_onetopic @sol @javascript
Feature: Either add new tab to the end or in current position
  In order to prevent inserting tabs in Template position
  As a site administrator
  I can force adding new sections to the end of the course sections

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email            |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | format   | coursedisplay | numsections |
      | Course 1 | C1        | onetopic | 0             | 5           |
    And the following "activities" exist:
      | activity   | name                   | intro                         | course | idnumber    | section |
      | assign     | Test assignment name   | Test assignment description   | C1     | assign1     | 0       |
      | book       | Test book name         | Test book description         | C1     | book1       | 1       |
      | chat       | Test chat name         | Test chat description         | C1     | chat1       | 4       |
      | choice     | Test choice name       | Test choice description       | C1     | choice1     | 5       |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |

  Scenario: Adding a section in middle of tabs
    Given the following config values are set as admin:
      | allowmidtab | 1 | format_onetopic |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    When I click on "Topic 5" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    And I should see "Test choice name" in the "#page-content li#section-5" "css_element"
    And I click on "Topic 4" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    And I follow "Add a section after the currently selected section"
    And I click on "Topic 5" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Topic 6"
    And I should not see "Test choice name" in the "#page-content li#section-5" "css_element"
    And ".format_onetopic-tabs .tab_position_7 .nav-link" "css_element" should not exist

  Scenario: Adding a section to end of tabs
    Given the following config values are set as admin:
      | allowmidtab | 0 | format_onetopic |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    When I click on "Topic 5" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    And I should see "Test choice name" in the "#page-content li#section-5" "css_element"
    And I click on "Topic 4" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    And I follow "Add a section at the end"
    And I click on "Topic 5" "link" in the "#page-content ul.nav.nav-tabs" "css_element"
    Then I should see "Topic 6"
    And I should see "Test choice name" in the "#page-content li#section-5" "css_element"
    And ".format_onetopic-tabs .tab_position_7 .nav-link" "css_element" should not exist