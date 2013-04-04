<?php
/*
 *grab_mail.php
 *
 * Developed by Nachi
 *
 */
?>
<HTML>
<HEAD>
<TITLE>Test for Nachi's PHP POP3 class</TITLE>
</HEAD>
<BODY>
<?php
	require("classes/pop3.class.php");
	require('classes/mime_parser.php');
	require('classes/rfc822_addresses.php');
    
	stream_wrapper_register('pop3', 'pop3_stream'); 
	
	$pop3=new pop3_class;
	$pop3->hostname="pop3.live.com";             /* POP 3 server host name                      */
	$pop3->port=995;                         /* POP 3 server host port, usually 110 but some servers use other ports Gmail uses 995 */
	$pop3->tls=1;                            /* Establish secure connections using TLS      */
	$user="*****@live.com";                        /* Authentication user name                    */
	$password="*******";                    /* Authentication password                     */
	$pop3->realm="";                         /* Authentication realm or domain              */
	$pop3->workstation="";                   /* Workstation for NTLM authentication         */
	$apop=0;                                 /* Use APOP authentication                     */
	$pop3->authentication_mechanism="USER";  /* SASL authentication mechanism               */
	$pop3->debug=0;                          /* Output debug information                    */
	$pop3->html_debug=0;                     /* Debug information is in HTML                */
	$pop3->join_continuation_header_lines=0; /* Concatenate headers split in multiple lines */

	if(($error=$pop3->Open())=="")
	{
		echo "<PRE>Connected to the POP3 server &quot;".$pop3->hostname."&quot;.</PRE>\n";
		if(($error=$pop3->Login($user,$password,$apop))=="")
		{
			echo "<PRE>User &quot;$user&quot; logged in.</PRE>\n";
			if(($error=$pop3->Statistics($messages,$size))=="")
			{
				echo "<PRE>There are $messages messages in the mail box with a total of $size bytes.</PRE>\n";
				$count=$messages-3;
				for($i=$messages;$i>=$count;$i--)
				{
					if($messages>0)
					{
						$pop3->GetConnectionName($connection_name);
						$message=$i;
						$message_file='pop3://'.$connection_name.'/'.$message;
						$mime=new mime_parser_class;
						/*
						* Set to 0 for not decoding the message bodies
						*/
						$mime->decode_bodies =1;
						$parameters=array(
							'File'=>$message_file,
							/* Read a message from a string instead of a file */
							//'Data'=>$message_file,              
							/* Save the message body parts to a directory     */
							/* 'SaveBody'=>'/tmp',                            */
							/* Do not retrieve or save message body parts     */
							'SkipBody'=>0,
						);
						$success=$mime->Decode($parameters, $decoded);
	
						if(!$success)
						echo '<h2>MIME message decoding error: '.HtmlSpecialChars($mime->error)."</h2>\n";
						else
						{
							/*echo '<h2>MIME message decoding successful</h2>'."\n";
							echo '<h2>Message structure</h2>'."\n";
							echo '<pre>';
							var_dump($decoded);
							echo '</pre>';*/
							//echo "Message id".$decoded[0]["Headers"]["x-message-delivery:"];
							$message_id=$decoded[0]["Headers"]["x-message-delivery:"];
							if($mime->Analyze($decoded[0], $results))
							{
								echo "Subject :".$results['Subject']."<br>";
								echo "From :".$results['From']."<br>";
								echo "To :".$results['To']."<br>";
								echo "Sent :".$results['Date']."<br>";
								echo "Data :".$results['Data']."<br>";
							}
							else
								echo 'MIME message analyse error: '.$mime->error."\n";	
						}
					}
				}
				if($error==""&& ($error=$pop3->Close())=="")
				echo "<PRE>Disconnected from the POP3 server &quot;".$pop3->hostname."&quot;.</PRE>\n";
			}
		}
	}
	if($error!="")
	echo "<pre>Error: ",HtmlSpecialChars($error),"</pre>";
?>
</BODY>
</HTML>