<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class MotivationalQuotes extends Module
{
    public function __construct()
    {
        $this->name = 'motivationalquotes';
        $this->version = '1.0.0';
        $this->author = 'Khelifa Abdelkarim';
        $this->need_instance = 0;
        $this->bootstrap = true; 

        parent::__construct();

        $this->displayName = $this->l('Motivational Quotes');
        $this->description = $this->l('Displays a random motivational quote on the homepage.');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayHome($params)
    {
        $quotes = [
            "Believe in yourself!",
            "Every day is a second chance.",
            "Push yourself, because no one else will.",
            "Success doesn’t just find you. You have to go out and get it.",
            "Your limitation—it’s only your imagination."
        ];

        // Select a random quote
        $random_quote = $quotes[array_rand($quotes)];

        // Assign the quote to the template
        $this->context->smarty->assign('quote', $random_quote);

        // Render the template
        return $this->display(__FILE__, 'views/templates/hook/displayHome.tpl');
    }
}
