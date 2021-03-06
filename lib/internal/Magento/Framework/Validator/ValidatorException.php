<?php
/**
 * \Exception class for validator
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Validator;

/**
 * Exception to be thrown when an validation data is failed
 */
class ValidatorException extends \Magento\Framework\Exception\InputException
{
    /**
     * @var array
     */
    protected $messages = [];

    /**
     * Constructor
     *
     * @param string $message
     * @param [] $params
     * @param \Exception $cause
     * @param array $messages Validation error messages
     */
    public function __construct(
        $message = self::DEFAULT_MESSAGE,
        $params = [],
        \Exception $cause = null,
        array $messages = []
    ) {
        if (!empty($messages)) {
            $message = '';
            foreach ($messages as $propertyMessages) {
                foreach ($propertyMessages as $propertyMessage) {
                    if ($message) {
                        $message .= PHP_EOL;
                    }
                    $message .= $propertyMessage;
                    $this->addMessage(new \Magento\Framework\Message\Error($propertyMessage));
                }
            }
        }
        parent::__construct($message, $params, $cause);
    }

    /**
     * Setter for message
     *
     * @param \Magento\Framework\Message\AbstractMessage $message
     * @return $this
     */
    public function addMessage(\Magento\Framework\Message\AbstractMessage $message)
    {
        if (!isset($this->messages[$message->getType()])) {
            $this->messages[$message->getType()] = [];
        }
        $this->messages[$message->getType()][] = $message;
        return $this;
    }

    /**
     * Getter for messages by type or all
     *
     * @param string $type
     * @return array
     */
    public function getMessages($type = '')
    {
        if ('' == $type) {
            $allMessages = [];
            foreach ($this->messages as $messages) {
                $allMessages = array_merge($allMessages, $messages);
            }
            return $allMessages;
        }
        return isset($this->messages[$type]) ? $this->messages[$type] : [];
    }
}
