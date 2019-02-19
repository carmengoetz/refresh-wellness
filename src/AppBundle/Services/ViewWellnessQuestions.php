<?php
namespace AppBundle\Services;

/**
 * ViewWellnessQuestions short summary.
 *
 * ViewWellnessQuestions description.
 *
 * @version 1.0
 * @author cst231
 */
class ViewWellnessQuestions
{
    /**
     * Takes in a user object and returns an array of all of the necessary information to tell
     * a front-end what to show on the page when attempting to view the wellness questions page.
     * @param mixed $user - User object (used to access the user's name)
     * @return array - array of all of the properties needed to tell the page what to show
     */
    public function generateArray($user)
    {
        //Create return objects to send to the page as JSON
        $return = array( 'status' => 'success',
            'data' => array(
                //contentHead portion of JSON response
                'contentHead'=> array(
                    //Text to show in the title portion of page (or whatever front-end wants to do with it)
                    'title'=>'How are you Feeling?'
                ),
                //contentBody portion of the JSON response
                'contentBody'=> array(
                    //Text to show on the page
                    'text'=> 'How are you feeling?',
                    //Array of input objects with attribute values to set
                    'inputs'=> array(
                        //Mood input
                        array(
                            'name'=> 'mood',
                            'type'=> 'range',
                            'min'=> 0,
                            'max'=> 10,
                            'value'=> 0
                            ),
                        //Energy input
                        array(
                            'name'=> 'energy',
                            'type'=> 'range',
                            'min'=> 0,
                            'max'=> 10,
                            'value'=> 0
                            ),
                        //Thoughts input
                        array(
                            'name'=> 'thoughts',
                            'type'=> 'range',
                            'min'=> 0,
                            'max'=> 10,
                            'value'=> 0
                            ),
                        //Sleep input
                        array(
                            'name'=> 'sleep',
                            'type'=> 'range',
                            'min'=> 0,
                            'max'=> 10,
                            'value'=> 0
                            )
                        )
                    ),
                    //Username of logged in user (page can use as needed)
                    'userName'=> $user->getName()),
                'message' =>''

            );

        return $return;
    }
}