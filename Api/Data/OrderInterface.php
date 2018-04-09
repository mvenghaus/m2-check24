<?php

namespace Inkl\Check24\Api\Data;

interface OrderInterface
{

    const KEY_ID = 'id';
    const KEY_FILENAME = 'filename';
    const KEY_CONTENT = 'content';
    const KEY_PROCESSED = 'processed';
    const KEY_MAGENTO_ORDER_ID = 'magento_order_id';
    const KEY_MAGENTO_ORDER_INCREMENT_ID = 'magento_order_increment_id';
    const KEY_ERROR = 'error';
    const KEY_ERROR_MESSAGE = 'error_message';
    const KEY_UPDATED_AT = 'updated_at';
    const KEY_CREATED_AT = 'created_at';

    /** @return $this */
    public function setId($id);

    public function getId();

    /** @return $this */
    public function setFilename($filename);

    public function getFilename();

    /** @return $this */
    public function setContent($content);

    public function getContent();

    /** @return $this */
    public function setProcessed($processed);

    public function getProcessed();

    /** @return $this */
    public function setMagentoOrderId($magentoOrderId);

    public function getMagentoOrderId();

    /** @return $this */
    public function setMagentoOrderIncrementId($magentoOrderIncrementId);

    public function getMagentoOrderIncrementId();

    /** @return $this */
    public function setError($error);

    public function getError();

    /** @return $this */
    public function setErrorMessage($errorMessage);

    public function getErrorMessage();

    /** @return $this */
    public function setUpdatedAt($updatedAt);

    public function getUpdatedAt();

    /** @return $this */
    public function setCreatedAt($createdAt);

    public function getCreatedAt();

}