<?php

namespace App\Domain\Shared\Validator;

use Kiczort\PolishValidator\NipValidator;
use Kiczort\PolishValidator\PeselValidator;
use Kiczort\PolishValidator\RegonValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PolishNumberValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PolishNumber) {
            throw new UnexpectedTypeException($constraint, PolishNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!in_array($constraint->type, [
            PolishNumber::TYPE_PESEL,
            PolishNumber::TYPE_NIP,
            PolishNumber::TYPE_REGON,
            PolishNumber::TYPE_ID,
        ])) {
            throw new MissingOptionsException('Missing type validator.', []);
        }

        switch ($constraint->type) {
            case PolishNumber::TYPE_PESEL:
                $this->peselValidate($value, $constraint);
                break;
            case PolishNumber::TYPE_NIP:
                $this->nipValidate($value, $constraint);
                break;
            case PolishNumber::TYPE_REGON:
                $this->regonValidate($value, $constraint);
                break;
            case PolishNumber::TYPE_ID:
                $this->idValidate($value, $constraint);
                break;
        }
    }

    private function peselValidate(string $value, PolishNumber $constraint): void
    {
        $validator = new PeselValidator();
        if (!$validator->isValid($value, ['strict' => $constraint->strict])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ number }}', $value)
                ->setParameter('{{ type }}', 'PESEL')
                ->addViolation();
        }
    }

    private function nipValidate(string $value, PolishNumber $constraint): void
    {
        $validator = new NipValidator();
        if (!$validator->isValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ number }}', $value)
                ->setParameter('{{ type }}', 'NIP')
                ->addViolation();
        }
    }

    private function regonValidate(string $value, PolishNumber $constraint): void
    {
        $validator = new RegonValidator();
        if (!$validator->isValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ number }}', $value)
                ->setParameter('{{ type }}', 'REGON')
                ->addViolation();
        }
    }

    private function idValidate(string $value, PolishNumber $constraint): void
    {
        if (9 != strlen($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ number }}', $value)
                ->setParameter('{{ type }}', 'ID')
                ->addViolation();
        }
    }
}
