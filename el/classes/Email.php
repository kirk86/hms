<?php

class EMAIL {

	private $to;
	private $from;
	private $subject;
	private $body;
	private $cc;
	private $bcc;
	private $senderName;
	private $replyTo;
	private $xMailer;
	private $contentType;
	private $file; // TODO: Filesize
	private $remotePath; // TODO
	private $limitTotalAttachmentSize; // TODO
		
	public function addTo($to) {
		// get the last index of $this->to
		$count = count($this->to);
	
		// If $to is an array
		if(is_array($to)) {
			// Check if all are correct email address
			if(!filter_var_array($to, FILTER_VALIDATE_EMAIL)) {
				return -1;
			}
			//add all to $this->to;
			foreach($to as $key=>$val) {
				$this->to[$count++] = $val; // adding and incrementing count
			}
		} else { // If $to is single email
			if(!filter_var($to, FILTER_VALIDATE_EMAIL)) {
				return -2;
			}
			// Add it to $this->to;
			$this->to[$count] = $to;
		}
		return 0;
	}
	public function clearTo() {
		$this->to = NULL;
	}
	public function addFrom($from) {
		// Validate $from
		if(!filter_var($from, FILTER_VALIDATE_EMAIL)) {
			return -1;
		}
		// add to $this->from
		$this->from = $from;
		return 0;
	}
	public function addSubject($sub, $type=1) { // $type = 1: Add/Replace   $type =2: Append
		if($type == 1) {			// Add/Replace
			$this->subject = $sub;
		} else if($type == 2) {
			if($this->subject != NULL) {  	// If not null append
				$this->subject .= $sub;
			} else {  			// If NULL add
				$this->subject = $sub;
			}
		} else {
			return -1;
		}
		return 0;
	}
	public function addBody($body, $type=1) { // $type = 1: Add/Replace   $type =2: Append
		if($type == 1) {			// Add/Replace
			$this->body = $body;
		} else if($type == 2) {
			if($this->body != NULL) {  	// If not null append
				$this->body .= $body;
			} else {  			// If NULL add
				$this->body = $body;
			}
		} else {
			return -1;
		}
		return 0;
	}
	public function addCc($cc) {
		// get the last index of $this->cc
		$count = count($this->cc);
	
		// If $to is an array
		if(is_array($cc)) {
			// Check if all are correct email address
			if(!filter_var_array($cc, FILTER_VALIDATE_EMAIL)) {
				return -1;
			}
			//add all to $this->cc;
			foreach($cc as $key=>$val) {
				$this->cc[$count++] = $val; // adding and incrementing count
			}
		} else { // If $cc is single email
			if(!filter_var($cc, FILTER_VALIDATE_EMAIL)) {
				return -2;
			}
			// Add it to $this->cc;
			$this->cc[$count] = $cc;
		}
		return 0;
	}
	public function clearCc() {
		$this->cc = NULL;
	}
	
	public function addBcc($bcc) {
		// get the last index of $this->bcc
		$count = count($this->bcc);
	
		// If $to is an array
		if(is_array($bcc)) {
			// Check if all are correct email address
			if(!filter_var_array($bcc, FILTER_VALIDATE_EMAIL)) {
				return -1;
			}
			//add all to $this->bcc;
			foreach($bcc as $key=>$val) {
				$this->bcc[$count++] = $val; // adding and incrementing count
			}
		} else { // If $bcc is single email
			if(!filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
				return -2;
			}
			// Add it to $this->bcc;
			$this->bcc[$count] = $bcc;
		}
		return 0;
	}
	public function clearBcc() {
		$this->bcc = NULL;
	}
	public function addSenderName($name, $type = 1) {  // $type: 1-Add/Replace 2-Append
		if(!(is_string($name) || is_null($name))) {
			return -1;	
		}
		if($type == 1) {
			$this->senderName = $name;
		} else {
			if($this->senderName == NULL) {
				$this->senderName = $name;
			}else {
				$this->senderName .= $name;
			}
		}
		return 0;
	}
	public function addReplyTo($reply) {
		// Validate $reply
		if(!filter_var($reply, FILTER_VALIDATE_EMAIL)) {
			return -1;
		}
		// add to $this->replyTo
		$this->replyTo = $reply;
		return 0;
	}
	public function setXmailer($xMailer = 1) {  // 1- attach mailer    0/NULL-Do not add mailer
		if($xMailer == 1 || $xMailer == 0 || $xMailer == NULL) {
			$this->xMailer = $xMailer;
			return 0;
		} else {
			return -1;
		}
	}
	public function addFiles($file) { // Only strings or array with string elements allowed.
		$count = count($this->file);
		$return = 0;  				// Stores number of skipped vals if they are not string
		if(is_array($file)) {
			foreach($file as $key=>$val) {
				if(is_string($val)) {
					//  TODO: Check if file exists
					if(file_exists($val) === true) {
						$this->file[$count++] = $val;
					} else {
						$return ++; // Skipped cuz it does not exists
					}
				} else {
					$return++; // Skipped cuz its not string
				}
			}
		} else if(is_string($file)) {
			// TODO: Check if file exists
			if(file_exists($file) === true) {
				$this->file[$count++] = $file;
			} else {
				return -2; // Single file does not exists
			}
		} else {
			return -1; // Type is not string
		}
		return $return; // If return is 0 then return 0 else return number of vals escaped i.e. $return
	}
	public function clearFiles() {
		$this->file = NULL;
	}
	public function setContentType($type) { // html or plain
		if($type === "html") {
			$this->contentType = "html";
		} else if($type === "plain") {
			$this->contentType = "plain";
		} else {
			return -1;
		}
		return 0;
	}
	public function send() {
		// Generate a unique id
		$uid = md5(uniqid(time()));
		
		// Check presence of essential components
		if(!is_array($this->to)) {
			return -1;
		}
		if($this->subject == NULL) {
			return -2;
		}
		if($this->body == NULL) {
			return -3;
		}
	
		// Prepare All the coponents
		
		// Prepare $to
		$to = "";
		$count = 0;
		foreach($this->to as $key=>$val) {
			if($count == 0) {
				$to = $val;
				$count++;
			} else {
				$to .= ", $val";
			}
		}
		
		// Prepare $cc
		// Check if $cc is present
		$cc = NULL;
		if(is_array($this->cc)) {
			$cc = "";
			$count = 0;
			foreach($this->cc as $key=>$val) {
				if($count == 0) {
					$cc = $val;
					$count++;
				} else {
					$cc .= ", $val";
				}
			}
		}

		// Prepare $bcc
		// Check if $bcc is present
		$bcc = NULL;
		if(is_array($this->bcc)) {
			$bcc = "";
			$count = 0;
			foreach($this->bcc as $key=>$val) {
				if($count == 0) {
					$bcc = $val;
					$count++;
				} else {
					$bcc .= ", $val";
				}
			}
		}
		
		// Prepare header
		$header = "";
		
		// Check if From is present
		if($this->from) {
			$header .= "From: ". $this->senderName ."<" . $this->from . ">\r\n";
		}
		
		// Check if $cc is present;
		if($cc !== NULL) {
			$header .= "Cc: " . $cc . "\r\n";
		}
		// Check if $cc is present;
		if($bcc !== NULL) {
			$header .= "Bcc: " . $bcc . "\r\n";
		}
		// Check if reply to is present
		if($this->replyTo != NULL) {
			$header .= "Reply-To: " . $this->replyTo . "\r\n";
		}
		// Check if xmailer is present
		if($this->xMailer == 1) {
			$header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
		}
		
		// Add basic header
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		
		// ADDING BODY 
		$header .= "--".$uid."\r\n";
		if($this->contentType != NULL) {
			$header .= "Content-type:text/{$this->contentType}; charset=iso-8859-1\r\n";
		} else {
			$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		}
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$header .= $this->body."\r\n\r\n";
		
		// ATTACHMENT
		if(is_array($this->file)) {
			foreach($this->file as $key=>$val) {
				$file= $val;
				
				$content = file_get_contents($file);
				$content = chunk_split(base64_encode($content));
		    		$name = basename($file);
	    		
				$header .= "--".$uid."\r\n";
				$header .= "Content-Type: application/octet-stream; name=\"".$file."\"\r\n"; // use different content types here
				$header .= "Content-Transfer-Encoding: base64\r\n";
				$header .= "Content-Disposition: attachment; filename=\"".$file."\"\r\n\r\n";
				$header .= $content."\r\n\r\n";
			}
			//$header .= "--".$uid."\r\n";
			$header .= "--".$uid."--\r\n";
		}
		/*
		if($_GET['attach'] == 1) {
			//echo "Attachment";
			$file = "Resume.pdf";
			$content = file_get_contents($file);
			$content = chunk_split(base64_encode($content));
			$uid = md5(uniqid(time()));
	    		$name = basename($file);
	    		//echo $content;
	    		
	    		$file2 = "majorProject.doc";
			$content2 = file_get_contents($file2);
			$content2 = chunk_split(base64_encode($content2));
			$uid2 = md5(uniqid(time()));
	    		$name2 = basename($file2);
	    		
			
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
			$header .= "This is a multi-part message in MIME format.\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
			$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$header .= $this->body."\r\n\r\n";
			
			$header .= "--".$uid."\r\n";
			$header .= "Content-Type: application/octet-stream; name=\"".$file."\"\r\n"; // use different content types here
			$header .= "Content-Transfer-Encoding: base64\r\n";
			$header .= "Content-Disposition: attachment; filename=\"".$file."\"\r\n\r\n";
			//$header .= "X-Attachment-Id: 0.2" . "\r\n\r\n";
			$header .= $content."\r\n\r\n";
			
			$header .= "--".$uid."\r\n";
			$header .= "Content-Type: application/octet-stream; name=\"".$file2."\"\r\n"; // use different content types here
			$header .= "Content-Transfer-Encoding: base64\r\n";
			$header .= "Content-Disposition: attachment; filename=\"".$file2."\"\r\n\r\n";
			//$header .= "X-Attachment-Id: 0.1" . "\r\n\r\n";
			$header .= $content2."\r\n\r\n";
			
			$header .= "--".$uid."--";
			$this->body = "Maharana Pratap";
		}
		*/
		
		// Send the mail
		if(mail($to, $this->subject, $this->body, $header)) {
			echo "<div class='ui-state-highlight ui-corner-all' style='margin-top: 20px; padding: 0 .7em;'>
                       <p>
                       <span class='ui-icon ui-icon-info' style='float: left; margin-right: .3em;'></span>
                        Your message has been sent successfully.
                       </p>
                       </div>";
		} else {
			echo "<div class='ui-state-error ui-corner-all' style='padding: 0 .7em;'>
                      <p>
                      <span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span>
                      Your message has not been sent.
                      </p>
                      </div>";
		}
		return 0;
	}
	function debug($var) {
		var_dump($this->file);
	}
}

/* EXAMPLE

function eol() {
	echo "<br />";
}

$mail = new EMAIL();

$to[] = "giannismitros@gmail.com";
//$cc[] = "giannismitros@yahoo.com";
//$mail->addTo("maxsisco@gmail.com");
//$mail->addTo("tzakou@yahoo.com");

echo $mail->addTo($to);
eol();
//echo $mail->addCc($cc);
//eol();
echo $mail->addFrom("billgates@microsoft.com");
eol();
//echo $mail->addReplyTo("giannismitros@gmail.com");
//eol();
echo $mail->addSubject("Test");
eol();
echo $mail->addSenderName("testing Attachment 01");
eol();
echo $mail->addBody("<strong>Who knows</strong>");
eol();
echo $mail->addFiles("Resume.pdf");
eol();
echo $mail->addFiles("majorProject.doc");
eol();
echo $mail->setContentType("html");
eol();
//echo $mail->setXmailer();
//eol();
echo $mail->send();
eol();
var_dump($mail);
eol();
*/