<?php 
class Efiltry_Ceneo_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success 
{

    private function getOrder() {

        return Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());
    }

    public function getCeneoUserGuid() {

        return Mage::getStoreConfig('ceneo_options/ceneo_settings/cene_user_guid');
    }

    private function getWorkDaysToSendQuestionnaire() {

        return Mage::getStoreConfig('ceneo_options/ceneo_settings/work_days_to_send_questionnaire');
    }

    private function getAllowSendEmailMessage() {
        $message = Mage::getStoreConfig('ceneo_options/ceneo_settings/allow_send_email_message');

        return str_replace('{{store_name}}', Mage::getStoreConfig('general/store_information/name'), $message);
    }

    public function isReady() {

        return trim($this->getCeneoUserGuid()) !== '';
    }

    public function getCeneoParams() {
        $order = $this->getOrder();
        $orderedProductIds = [];
        foreach ($order->getAllVisibleItems() as $item) {

            for ($i=0; $i < (int)$item->getQtyOrdered(); $i++) { 

                $orderedProductIds[] = $item->getData('product_id');
            }
        }

        return (object)array(
            'client_email' => $order->customer_email,
            'order_id' => $this->getOrderId(),
            'shop_product_ids' => '#' . join("#", $orderedProductIds),
            'work_days_to_send_questionnaire' => $this->getWorkDaysToSendQuestionnaire(),
            'ceneo_guid' => $this->getCeneoUserGuid(),
            'allowSendEmailMessage' => $this->getAllowSendEmailMessage(),
            'is_ready' => $this->isReady()
        );
    }
}
