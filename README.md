Grabmail
========

PHP Script for grabbing the mail of Hotmail, Yahoo, Gmail etc.. using POP3 Class.

You can edit the code with your login credentials to see your mails

Just find the following lines in <code>grab_mail.php</code> and enter your email and password

<code>
$user="**********"   /* Authentication email        */
$password="*******"; /* Authentication password     */
</code>

change the pop3 server according to your provider.

<code>
$pop3->hostname="pop.gmail.com";  /* POP 3 server host name for gmail */
$pop3->hostname="pop3.live.com";  /* POP 3 server host name for hotmail */
$pop3->hostname="pop.mail.yahoo.com";  /* POP 3 server host name for yahoo */
</code>
