<?php
/**
 * Base class for the CPNV intranet resources
 *
 */

Yii::setPathOfAlias('PhpActiveResource', dirname(__FILE__));
Yii::import('PhpActiveResource.AuthenticatedActiveResourceBase', true);
Yii::import('PhpActiveResource.CurlTransporter', true);
Yii::import('PhpActiveResource.JsonSerializer', true);
Yii::import('PhpActiveResource.TypeMarshaller', true);

class ActiveResource extends AuthenticatedActiveResourceBase {

  var $site = 'http://intranet.cpnv.ch/';
  var $app_key = 'survey';
  var $app_secret = '1a03e18a0f5bfad9cbdc069020f0e39d6f227e3f6f36037ab4f8377010da738a577a2ff6f3e8e4d6b7a62f4672fc2f6b71a1424fec570e15d552294933053afa';
  
  static $marshaller;

  function __construct ($data = array ()) {
    if (!$this->transporter) $this->transporter = new CurlTransporter();
    if (!$this->serializer) $this->serializer = new JsonSerializer(self::$marshaller);

    parent::__construct($data);
  }
}

ActiveResource::$marshaller = new TypeMarshaller(array(
  'Cpnv::Student' => 'IntranetUser',
  'Cpnv::FormerStudent' => 'IntranetUser',
  'Cpnv::CurrentStudent' => 'IntranetUser',
  'Cpnv::Collaborator' => 'IntranetUser',
  'Cpnv::Teacher' => 'IntranetUser',
  'Cpnv::HeadTeacher' => 'IntranetUser',
  'Cpnv::Dean' => 'IntranetUser',
  'Cpnv::Class' => 'IntranetClass'
));

?>