<?php

namespace Inkl\Check24\Model\Reader;

class OpenTransOrder
{
    private $dom;

    public function load($content)
    {
        $this->dom = new \DOMDocument();
        $this->dom->loadXML($content);

        return $this;
    }

    public function getPartnerId()
    {
        return str_replace('TS-SA-', '', $this->xpathQuery('//SUPPLIER_IDREF'));
    }

    public function getOrderId()
    {
        return $this->xpathQuery('//ORDER_ID');
    }

    public function getCurrencyCode()
    {
        return 'EUR';
    }

    public function getInvoiceEmail()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//EMAIL");
    }

    public function getInvoiceCompany()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//NAME");
    }

    public function getInvoiceFirstname()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//NAME2");
    }

    public function getInvoiceLastname()
    {
        $lastname = $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//NAME3");

        $lastname = str_replace(' (nur Rechnungsadresse)', '', $lastname);

        return $lastname;
    }

    public function getInvoiceStreet()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//STREET");
    }

    public function getInvoiceRemarks()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//ADDRESS_REMARKS");
    }

    public function getInvoicePostcode()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//ZIP");
    }

    public function getInvoiceCity()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//CITY");
    }

    public function getInvoiceCountryCode()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//COUNTRY_CODED");
    }

    public function getInvoiceRegionId()
    {
        return '';
    }

    public function getInvoicePhone()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='invoice']/following-sibling::ADDRESS//PHONE");
    }

    public function getDeliveryCompany()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//NAME");
    }

    public function getDeliveryFirstname()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//NAME2");
    }

    public function getDeliveryLastname()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//NAME3");
    }

    public function getDeliveryStreet()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//STREET");
    }

    public function getDeliveryRemarks()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//ADDRESS_REMARKS");
    }

    public function getDeliveryPostcode()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//ZIP");
    }

    public function getDeliveryCity()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//CITY");
    }

    public function getDeliveryCountryCode()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//COUNTRY_CODED");
    }

    public function getDeliveryRegionId()
    {
        return '';
    }

    public function getDeliveryPhone()
    {
        return $this->xpathQuery("//PARTY_ROLE[text()='delivery']/following-sibling::ADDRESS//PHONE");
    }

    public function getOrderItems()
    {
        $xpath = new \DOMXPath($this->dom);

        $orderItems = [];
        $orderItemElements = $xpath->query('//ORDER_ITEM');

        /** @var \DOMElement $domElement */
        foreach ($orderItemElements as $orderItemElement)
        {
            $orderItems[] = [
                'sku' => $this->xpathQuery('.//SUPPLIER_PID', '', $orderItemElement),
                'name' => $this->xpathQuery('.//REMARK[@type=\'product_name\']', '', $orderItemElement),
                'price' => $this->xpathQuery('.//PRICE_AMOUNT', '', $orderItemElement),
                'qty' => (int)$this->xpathQuery('.//QUANTITY', '', $orderItemElement)
            ];
        }

        return $orderItems;
    }

    private function xpathQuery($query, $default = '', $contextNode = null)
    {
        $xpath = new \DOMXPath($this->dom);

        $nodes = $xpath->query($query, $contextNode);
        foreach ($nodes as $node)
        {
            return $node->nodeValue;
        }

        return $default;
    }

}
