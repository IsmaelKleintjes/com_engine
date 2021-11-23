<?php defined('_JEXEC') or die;
# -------------------------------------------------------------------------------------------------------
#	Copyright:	Edit4U Webservices BV
#	Written by:	Edwin Steenwinkel
#				www.edit4u.nl
# -------------------------------------------------------------------------------------------------------
jimport( 'joomla.mail.mail' );

/**
 * Class Message4U
 *
 * @version     1.0
 * @since       21-11-2016
 */
class Message4U {


    # -----------------------------------------------------------------------------------------------------------------------------

    var $_receiverName = NULL;
    var $_receiverEmail = NULL;

    # -----------------------------------------------------------------------------------------------------------------------------

    /**
     * Sends the email
     *
     * @param bool $attr
     *
     * @return bool|JException
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public static function send($attr=false )
    {
        $JMail = new JMail();
        $user = JFactory::getUser();
        $app = JFactory::getApplication();

        if( strlen($attr['trigger']) ) {
            $email = Message4U::getContent( $attr['trigger'] );
        }
        if( $attr['templateId'] > 0 ) {
            $email = Message4U::getTemplate( $attr['templateId'] );
        }

        $userId = $attr['user_id'];

        $isEmail = empty($attr['type']) ? $email->is_email : $attr['type']['is_email'];
        $isNotification = empty($attr['type']) ? $email->is_notification : $attr['type']['is_notification'];

        // Set sender
        $sender[0] = empty( $attr['sender_email'] ) ? $email->email_sender : $attr['sender_email'];
        $sender[1] = empty( $attr['sender_name'] ) ? $email->name_sender : $attr['sender_name'];
        $JMail->setSender( $sender );

        // Add recipient
        $JMail->addRecipient( $attr['to'] );
        $JMail->addCc( $attr['cc'] );
        $JMail->addBcc( $attr['bcc'] );

        if(isset($attr['reply_to']) && strlen($attr['reply_to'])){
            $JMail->addReplyTo( $attr['reply_to'], isset($attr['reply_to_name']) ?  $attr['reply_to_name'] : $attr['reply_to'] );
        }

        // Get subject and body
        if(!empty($attr['subject'])){
            $subject = $attr['subject'];
        } else {
            $subject = !empty( $attr['translate'] ) ? ($attr['lang_id'] ? language4UHelper::getByLanguage($email, 'email_subject', 'message', $attr['lang_id']) : language4UHelper::get($email, 'email_subject', 'message', false)) : $email->email_subject;
        }

        if(!empty($attr['notification_subject'])){
            $notificationSubject = $attr['notification_subject'];
        } else {
            $notificationSubject = !empty( $attr['translate'] ) ? ($attr['lang_id'] ? language4UHelper::getByLanguage($email, 'notification_subject', 'message', $attr['lang_id']) : language4UHelper::get($email, 'notification_subject', 'message', false)) : $email->notification_subject;
        }

        if(!empty($attr['body'])){
            $body = $attr['body'];
        } else {
            $body = !empty( $attr['translate'] ) ? ($attr['lang_id'] ? language4UHelper::getByLanguage($email, 'email', 'message', $attr['lang_id']) : language4UHelper::get($email, 'email', 'message', false)) : $email->email;
        }

        if(!empty($attr['notification_body'])){
            $notificationBody = $attr['notification_body'];
        } else {
            $notificationBody = !empty( $attr['translate'] ) ? ($attr['lang_id'] ? language4UHelper::getByLanguage($email, 'notification', 'message', $attr['lang_id']) : language4UHelper::get($email, 'notification', 'message', false)) : $email->notification;
        }

        $crudName = !empty( $attr['crud_name'] ) ? $attr['crud_name'] : Input4U::get('task', 'POST');
        if(!$crudName) {
            $crudName = Input4U::get('task', 'GET');
        }
        if(!$crudName) {
            $crudName = Input4U::get('task', 'REQUEST');
        }

        $crudId = !empty( $attr['crud_id'] ) ? $attr['crud_id'] : Input4U::getInt('id', 'REQUEST');

        // Replaces
        $attr['replaces']['base'] = JUri::root();
        if( is_array( $attr['replaces'] ) ) {
            $subject = Message4U::replace( $attr['replaces'], $subject );
            $notificationSubject = Message4U::replace( $attr['replaces'], $notificationSubject );
            $body = Message4U::replace( $attr['replaces'], $body );
            $notificationBody = Message4U::replace( $attr['replaces'], $notificationBody );
            $template = Message4U::replace( $attr['replaces'], $email->template );
        }

        // Attachment(s)
        if(is_array($attr['attachment'])) foreach($attr['attachment'] as $attachment) {
            if(!is_array($attachment)){
                $JMail->addAttachment( $attachment );
            } else {

                $JMail->addAttachment( $attachment['file'], $attachment['name'] );
            }
        }

        // Create unique hash
        $hash = JApplicationHelper::getHash($email->id.$attr['to'].$subject.date("Y-m-d H:i:s"));

        // Save log
        $tos = $attr['to'];
        if(!is_array($tos)){
            $tos = array($tos);
        }

        foreach($tos as $to){
            Message4U::saveLog( $email->id, $userId, $to, $subject, $body, $notificationSubject, $notificationBody, $isEmail, $isNotification, $hash, $crudName, $crudId);
        }

        // Check if opened
        $openedImage = "<img src='".JUri::root(false)."cli/checkopened.php?hash=".$hash."' style='height:1px;width:1px;display:none;'>";
        $template = str_replace( "{EMAIL_CHECK_OPEN}", $openedImage, $template );

        $emailBody = '';
        $emailBody .= '<!DOCTYPE html>';
        $emailBody .= '<html lang="nl-nl" dir="ltr">';
        $emailBody .= '<head>';
        $emailBody .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $emailBody .= '<title>' . $subject . '</title>';
        $emailBody .= '<style>' . $email->style . '</style>';
        $emailBody .= '</head>';
        $emailBody .= '<body>';
        $emailBody .= '<style>' . $email->style . '</style>';
        $emailBody .= str_replace( "{EMAIL_BODY}", $body, $template );
        $emailBody .= '</body>';
        $emailBody .= '</html>';

        // Set subject and body
        $JMail->isHtml( true );
        $JMail->setSubject( $subject );
        $JMail->setBody( $emailBody );
        $JMail->AltBody = JMailHelper::cleanText( strip_tags( str_replace( "{EMAIL_BODY}", $body, $template )));

        // SMTP
        if($isEmail){
            if(strtolower($app->get('mailer'))=='smtp') {
                $JMail->useSmtp(
                    $app->get('smtpauth'),
                    $app->get('smtphost'),
                    $app->get('smtpuser'),
                    $app->get('smtppass'),
                    $app->get('smtpsecure'),
                    $app->get('smtpport')
                );
            } elseif(strtolower($app->get('mailer'))=='sendmail') {
                $JMail->useSendmail($app->get('sendmail'));
            }


            return $JMail->Send();
        }

        // Send

        return true;
    }


    # -----------------------------------------------------------------------------------------------------------------------------

    private function saveLog( $messageId, $userId, $emailTo, $emailSubject, $emailBody, $notificationSubject, $notificationBody, $isEmail, $isNotification, $hash, $crudName, $crudId)
    {
        $messageLog = new stdClass();
        $messageLog->message_id = $messageId;
        $messageLog->user_id = $userId;
        $messageLog->hash = $hash;

        if($isEmail){
            $messageLog->email_to = $emailTo;
            $messageLog->email_subject = $emailSubject;
            $messageLog->email_body = $emailBody;
        }

        if($isNotification){
            $messageLog->notification_subject = $notificationSubject;
            $messageLog->notification_body = $notificationBody;
        }

        $messageLog->is_email = $isEmail;
        $messageLog->is_notification = $isNotification;

        $messageLog->crud_name = $crudName;
        $messageLog->crud_id = $crudId;

        $messageLog->created = date("Y-m-d H:i:s");

        return JFactory::getDbo()->insertObject('#__eng_message_log', $messageLog);
    }

    /**
     * Obtains mailcontent
     *
     * @param $id
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    private function getContent( $trigger )
    {
        $db = JFactory::getDbo();
        $q = $db->getQuery(true);

        $q->select($db->quoteName(array('m.title','m.email_subject','m.email', 'm.is_email', 'm.is_notification', 'm.notification_subject', 'm.notification', 'm.id')));
        $q->from($db->quoteName('#__eng_message','m'));
        $q->where($db->quoteName('m.trigger').' = '. $db->q($trigger));

        $q->select($db->quoteName(array('t.name_sender', 't.email_sender', 't.style', 't.template')));
        $q->leftJoin($db->quoteName('#__eng_message_template','t').' ON '.$db->quoteName('m.template_id').' = '.$db->quoteName('t.id'));

        $db->setQuery( $q->__toString() );
        return $db->loadObject();
    }

    # -----------------------------------------------------------------------------------------------------------------------------

    /**
     * Obtains the Mail Template
     *
     * @param $id
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    private function getTemplate($id )
    {
        $db = JFactory::getDbo();
        $q = $db->getQuery(true);

        $q->select($db->quoteName(array('name_sender', 'email_sender', 'style', 'template')));
        $q->from($db->quoteName('#__eng_message_template'));
        $q->where($db->quoteName('id').' = '.(int)$id );

        $db->setQuery( $q->__toString() );
        return $db->loadObject();
    }

    # -----------------------------------------------------------------------------------------------------------------------------

    /**
     * Replaces...
     *
     * @param $replaces
     * @param $string
     *
     * @return mixed
     *
     * @version     1.0
     * @since       21-11-2016
     */
    private function replace($replaces, $string )
    {
        foreach( $replaces as $find => $replace ) {
            $string = str_replace( "{".$find."}", $replace, $string );
        }
        return $string;
    }

    # -----------------------------------------------------------------------------------------------------------------------------
}