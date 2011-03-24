<?php

require_once 'class.hapilead.php';

$HAPIKey = 'some-really-long-API-Key';
//This first example returns a list of leads in one big chunk

	//Create new instance of HAPILeads class
	$test_one = new HAPILeads();
	//Set request type to list
	$test_one->setRequestType('list');
	//Set HAPIKey
	$test_one->setHAPIKey($HAPIKey);
	//Set various request filters
	$test_one->setRequestMax(10);
	$test_one->setRequestTimePivot('lastModifiedAt');
	$test_one->setRequestExcludeConversionEvents('true');

	//Echo out the URL that will be called by the Get Request (not necessary, just nice to see if you're testing)
	echo $test_one->createGetRequestURL() . '<br>';

	//Execute the Get Request and return the first set of leads in the list (this will not automatically paginate)
	$results = $test_one->executeGetRequest();

	//Echo out the results from the Get Request
	echo '<br>Error: ' . $results['Error'];
	echo '<br>ErrorMessage: ' . $results['ErrorMessage'];
	echo	 '<br>RecordCount: ' . $results['RecordCount'];
	echo '<br>Data:<br>';
	//Display all of the lead data returned
	print_r($results['Data']);

//This second example sets the parameters for the lead request and returns the leads one at a time so you can
//easily process them.  The executeGetNextRecord() function handles all of the pagination for you so you don't
//need to worry about making multiple requests to get the complete list of leads.

	//Initiate error and record counters
	$leaderror = 0;
	$counter = 0;
	//Create new instance of HAPILeads class
	$test_two = new HAPILeads();
	//Set HAPIKey
	$test_two->setHAPIKey($HAPIKey);
	//Set various request filters
	$test_two->setRequestExcludeConversionEvents('true');

	//Loop while the request is not returning an error
	while(($leaderror == 0))
	{
		$counter++;
		//Execute the Get Next Record function
		$lead = $test_two->executeGetNextRecord();
		
		//Set the returned error code to $leaderror.  It will be 0 unless there was an error.
		$leaderror = $lead['Error'];
		if ($leaderror == 0)
		{
			//Echo out a record counter and some sample data from the lead
			echo '<br>' . $counter . ' ';
			echo $lead['Data']->guid . ' ' . $lead['Data']->lastName;
		}
	}

//This last example will update some fields on an existing lead using executeUpdateLead()	
	//Create new instance of the HAPILeads class
	$test_three = new HAPILeads();
	//Set HAPIKey
	$test_three->setHAPIKey($HAPIKey);
	//Initiate $UpdateFields array where you'll store fieldname => value for each field you want to update
	$UpdateFields = array ();
	
	//Set some fields to be updated in the UpdateFields array
	//Notice how the name contains quotation marks in the string, this is to handle strings in the JSON data
	$UpdateFields['closedAt'] = 1297594846512;
	$UpdateFields['isCustomer'] = 'true';
	$UpdateFields['firstName'] = '"Test Name"';
	
	//Execute the Update Lead function
	$updateresults = $test_three->executeUpdateLead('826fa69b5a394ddeb6cd786a36cf4524', $UpdateFields);
	
	//Echo out the results of the udpate
	echo '<br>Error: ' . $updateresults['Error'];
    echo '<br>ErrorMessage: ' . $updateresults['ErrorMessage'];
    echo '<br>Data: ' . $updateresults['Data'];
            
?>