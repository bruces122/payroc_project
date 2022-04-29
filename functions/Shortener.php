<?php

require_once("config.php");

class Shortener
{
	protected $db;

	//open the database connection
	public function __construct()
	{
		$this->db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        
		if ($this->db->connect_errno)
		{
			header("Location: index.php?error=connection");
			die("Database Connection Error");
		}
	}

	public function __destruct()
	{
		//close the connection when finished with dbase
		$this->db->close();
	}

	//get the url based on the short code to redirect them to the long URL
	public function getLongSiteLocation($shortUrl)
	{
		//clean the url in case of injection
		$shortUrl = $this->cleanCode($shortUrl);

		try{
			//prep statement for execution, bind variable and execute and get results
			$state = $this->db->prepare("SELECT url_name FROM shortener WHERE short_code = ?");
			$state->bind_param('s', $shortUrl);
			$state->execute();
			$result = $state->get_result();
		}
		catch (exception $e){
			header("Location: index.php?error=dbase_err");
			die();
		}

		//check a record was returned and pass it back to redirect or send them to main page with error
        if ($result->num_rows)
		{
			return $result->fetch_object()->url_name;
		}
		else
		{
			header("Location: index.php?error=not_exist");
			die();
		}
	}

	//create the random unique code for link and validate not already in dbase, we are not going to check 
	//and see if URL is duplicate site as not necessary as it is very unlikely if really used
	public function createCode()
	{
		return substr(str_shuffle(str_repeat($x=CHARSET, ceil(URL_LENGTH/strlen($x)) )),1,URL_LENGTH);
		//return random_str(URL_LENGTH, CHARSET);
	}

	public function cleanCode($notClean)
	{
		//first remove any javascript tags
		$notClean = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $notClean);

		//now remove any other bad things that may be in there
        return $this->db->real_escape_string(strip_tags(addslashes($notClean)));
	}

	//insert the new site url in the dbase - be sure to validate the URL and check for injection
	public function validateReturnCode($longUrl)
	{

		//clean the url in case of injection
		$longUrl = $this->cleanCode($longUrl);

		//get the short url
		$shortUrl = $this->createCode();
		try{
			//validate the short url is unique in the dbase
			$state = $this->db->prepare("SELECT count(short_code) as my_count FROM shortener WHERE short_code = ?");
			$state->bind_param('s', $shortUrl);
			$state->execute();
			$result = $state->get_result();
		}
		catch (exception $e){
			header("Location: index.php?error=dbase_err");
			die();
		}
		while($result->fetch_object()->my_count > 0)
		{
			try{
				$state = $this->db->prepare("SELECT count(short_code) as my_count FROM shortener WHERE short_code = ?");
				$state->bind_param('s', $shortUrl);
				$state->execute();
				$result = $state->get_result();
			}
			catch (exception $e){
				header("Location: index.php?error=dbase_err");
				die();
			}
			//get a different short url
			$shortUrl = $this->createCode();
		}

		try{
			//now we save the long and short code to the dbase
			$state = $this->db->prepare("INSERT INTO shortener (short_code, url_name) VALUES (?,?)");
			$state->bind_param('ss', $shortUrl, $longUrl);
			$state->execute();
		}
		catch (exception $e){
			header("Location: index.php?error=dbase_err");
			die();
		}
		//return the short code to the index page
		echo json_encode(URL_BASE . $shortUrl);
		exit;
	}

}

?>