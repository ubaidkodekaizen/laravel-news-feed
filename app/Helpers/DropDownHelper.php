<?php

namespace App\Helpers;

class DropDownHelper
{
    /**
     * Render gender dropdown
     */
    public static function renderGenderDropdown($selected = null)
    {
        $options = [
            '' => 'Select Gender',
            'Male' => 'Male',
            'Female' => 'Female',
            'Other' => 'Other',
            'Prefer not to say' => 'Prefer not to say',
        ];

        return self::renderSelect('gender', 'gender', $options, $selected);
    }

    /**
     * Render age group dropdown
     */
    public static function renderAgeGroupDropdown($selected = null)
    {
        $options = [
            '' => 'Select Age Group',
            '18-24' => '18-24',
            '25-34' => '25-34',
            '35-44' => '35-44',
            '45-54' => '45-54',
            '55-64' => '55-64',
            '65+' => '65+',
            'Prefer not to say' => 'Prefer not to say',
        ];

        return self::renderSelect('age_group', 'age_group', $options, $selected);
    }

    /**
     * Render ethnicity dropdown
     */
    public static function renderEthnicityDropdown($selected = null)
    {
        $options = [
            '' => 'Select Ethnicity',
            'Asian' => 'Asian',
            'Black or African American' => 'Black or African American',
            'Hispanic or Latino' => 'Hispanic or Latino',
            'Native American or Alaska Native' => 'Native American or Alaska Native',
            'Native Hawaiian or Pacific Islander' => 'Native Hawaiian or Pacific Islander',
            'White or Caucasian' => 'White or Caucasian',
            'Other' => 'Other',
            'Prefer not to say' => 'Prefer not to say',
        ];

        return self::renderSelect('ethnicity', 'ethnicity', $options, $selected);
    }

    /**
     * Render nationality dropdown
     */
    public static function nationalityDropdown($selected = null)
    {
        $options = [
            '' => 'Select Nationality',
            'American' => 'American',
            'British' => 'British',
            'Canadian' => 'Canadian',
            'Australian' => 'Australian',
            'Indian' => 'Indian',
            'Pakistani' => 'Pakistani',
            'Bangladeshi' => 'Bangladeshi',
            'Other' => 'Other',
        ];

        return self::renderSelect('nationality', 'nationality', $options, $selected);
    }

    /**
     * Render marital status dropdown
     */
    public static function renderMaritalStatusDropdown($selected = null)
    {
        $options = [
            '' => 'Select Marital Status',
            'Single' => 'Single',
            'Married' => 'Married',
            'Divorced' => 'Divorced',
            'Widowed' => 'Widowed',
            'Prefer not to say' => 'Prefer not to say',
        ];

        return self::renderSelect('marital_status', 'marital_status', $options, $selected);
    }

    // Company-related methods removed - not part of newsfeed boilerplate:
    // renderBusinessTypeDropdown, renderCompanyExperienceDropdown, renderEmployeeSizeDropdown,
    // renderRevenueDropdown, designationDropdown, industryDropdown, formatRevenueForDisplay,
    // formatEmployeeSizeForDisplay, getBusinessLocationsArray, getCurrentBusinessChallengesArray,
    // getBusinessGoalsArray, getCompanyAttributesArray

    /**
     * Render a select dropdown
     */
    private static function renderSelect($name, $id, $options, $selected = null)
    {
        $html = '<select class="form-control" name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($id) . '">';
        
        foreach ($options as $value => $label) {
            $isSelected = ($selected !== null && $selected == $value) ? ' selected' : '';
            $html .= '<option value="' . htmlspecialchars($value) . '"' . $isSelected . '>' . htmlspecialchars($label) . '</option>';
        }
        
        $html .= '</select>';
        
        return $html;
    }
}
