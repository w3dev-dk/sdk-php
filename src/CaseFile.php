<?php
namespace Penneo\SDK;

class CaseFile extends Entity
{
	protected static $propertyMapping = array(
		'create' => array(
			'title',
			'metaData',
			'sendAt',
			'expireAt',
			'visibilityMode',
			'caseFileTypeId' => 'caseFileType->getId'
		),
		'update' => array(
			'title',
			'metaData',
			'caseFileTypeId' => 'caseFileType->getId'
		)
	);
	protected static $relativeUrl = 'casefiles';

	protected $title;
	protected $metaData;
	protected $sendAt;
	protected $expireAt;
	protected $visibilityMode;
	protected $status;
	protected $created;
	protected $signIteration;
	protected $caseFileType;

	public function __construct()
	{
		// Set default visibility mode
		$this->visibilityMode = 0;
	}

	public function getCaseFileTypes()
	{
		return parent::getLinkedEntities($this, 'Penneo\SDK\CaseFileType', 'casefile/casefiletypes');
	}
	
	public function getDocumentTypes()
	{
		if (!$this->id) {
			return array();
		}
		return parent::getLinkedEntities($this, 'Penneo\SDK\DocumentType', 'casefiles/'.$this->id.'/documenttypes');
	}

	public function getSignerTypes()
	{
		if (!$this->id) {
			return array();
		}
		return parent::getLinkedEntities($this, 'Penneo\SDK\SignerType', 'casefiles/'.$this->id.'/signertypes');
	}

	public function getDocuments()
	{
		return parent::getLinkedEntities($this, 'Penneo\SDK\Document');
	}

	public function getSigners()
	{
		return parent::getLinkedEntities($this, 'Penneo\SDK\Signer');
	}
	
	public function findSigner($id)
	{
		return parent::findLinkedEntity($this, 'Penneo\SDK\Signer', $id);
	}

	public function getErrors()
	{
		return parent::getAssets($this, 'errors');
	}

	public function activate()
	{
		return parent::callAction($this, 'activate');
	}

	public function send()
	{
		return parent::callAction($this, 'send');
	}

	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function getMetaData()
	{
		return $this->metaData;
	}
	
	public function setMetaData($meta)
	{
		$this->metaData = $meta;
	}
	
	public function getSendAt()
	{
		return new \DateTime('@'.$this->sendAt);
	}
	
	public function setSendAt(\DateTime $sendAt)
	{
		$this->sendAt = $sendAt->getTimestamp();
	}

	public function getExpireAt()
	{
		return new \DateTime('@'.$this->expireAt);
	}
	
	public function setExpireAt(\DateTime $expireAt)
	{
		$this->expireAt = $expireAt->getTimestamp();
	}

	public function getVisibilityMode()
	{
		return $this->visibilityMode;
	}
	
	public function setVisibilityMode($visibilityMode)
	{
		$this->visibilityMode = $visibilityMode;
	}

	public function getStatus()
	{
		switch ($this->status) {
			case 0:
				return 'new';
			case 1:
				return 'pending';
			case 2:
				return 'rejected';
			case 3:
				return 'deleted';
			case 4:
				return 'signed';
			case 5:
				return 'completed';
		}
	
		return 'deleted';
	}
	
	public function getCreatedAt()
	{
		return new \DateTime('@'.$this->created);
	}
	
	public function getSignIteration()
	{
		return $this->signIteration;
	}

	public function getCaseFileType()
	{
		if ($this->id && !$this->caseFileType) {
			$caseFileTypes = parent::getLinkedEntities($this, 'Penneo\SDK\CaseFileType');
			$this->caseFileType = $caseFileTypes[0];
		}
		return $this->caseFileType;
	}

	public function setCaseFileType(CaseFileType $type)
	{
		$this->caseFileType = $type;
	}
}
