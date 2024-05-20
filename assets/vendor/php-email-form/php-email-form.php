<?php
class PHP_Email_Form {
  public $to;
  public $from_name;
  public $from_email;
  public $subject;
  public $smtp = array();
  public $ajax = false;
  private $messages = array();

  public function add_message($content, $label, $priority = 10) {
    $this->messages[] = array(
      'content' => $content,
      'label' => $label,
      'priority' => $priority
    );
  }

  public function send() {
    if (!empty($this->smtp)) {
      return $this->send_smtp();
    } else {
      return $this->send_mail();
    }
  }

  private function send_mail() {
    $headers = "From: " . $this->from_name . " <" . $this->from_email . ">\r\n";
    $body = "";
    foreach ($this->messages as $message) {
      $body .= $message['label'] . ": " . $message['content'] . "\n";
    }
    return mail($this->to, $this->subject, $body, $headers);
  }

  private function send_smtp() {
    // Include PHPMailer library files
    require 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = $this->smtp['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $this->smtp['username'];
    $mail->Password = $this->smtp['password'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = $this->smtp['port'];

    $mail->setFrom($this->from_email, $this->from_name);
    $mail->addAddress($this->to);
    $mail->Subject = $this->subject;

    $body = "";
    foreach ($this->messages as $message) {
      $body .= $message['label'] . ": " . $message['content'] . "\n";
    }
    $mail->Body = $body;

    return $mail->send();
  }
}
?>
