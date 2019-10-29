<?php

namespace MakG\SymfonyUtilsBundle\Console\Input;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;

/**
 * It uses symfony/console component to parse search queries like "id:123 contains:string search phrases" after simple initial conversion.
 */
class SearchQueryInput extends StringInput
{
    private const SEARCH_OPTION_REGEX = '/([a-z0-9\-_]+):\s*/i';

    public function __construct(string $input)
    {
        $definition = new InputDefinition(
            array_merge(
                [new InputArgument('phrases', InputArgument::IS_ARRAY)],
                $this->extractInputOptionsFromSearchQuery($input)
            )
        );

        $input = $this->convertSearchQueryIntoCommandOptions($input);

        parent::__construct($input);

        $this->definition = $definition;
        $this->parse();
    }

    /**
     * Returns search phrases from input string.
     * Example: for string "symfony bundle site:github.com in-title:symfony rest api" it will return array [symfony, bundle, rest, api]
     */
    public static function getPhrasesFromInput(string $inputString): array
    {
        $input = new self($inputString);

        return $input->getArgument('phrases');
    }

    /**
     * Converts search string in `filter1:value filter2:"second value"` format into array of options.
     * The search string is first converted to command line options `--filter1=value --filter2="second value"
     * and then symfony/console component is used to parse them.
     */
    private function convertSearchQueryIntoCommandOptions(string $searchQuery): string
    {
        return preg_replace(self::SEARCH_OPTION_REGEX, '--\1=', $searchQuery);
    }

    private function extractInputOptionsFromSearchQuery(string $searchQuery): array
    {
        preg_match_all(self::SEARCH_OPTION_REGEX, $searchQuery, $matches);
        $inputOptions = [];

        foreach ($matches[1] ?? [] as $optionName) {
            $inputOptions[] = new InputOption(
                $optionName,
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY
            );
        }

        return $inputOptions;
    }
}
