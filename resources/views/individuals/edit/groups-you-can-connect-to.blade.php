<form class="stack" action="{{ localized_route('individuals.update-constituencies', $individual) }}" method="POST"
    enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('individuals.partials.progress')

        <div class="stack" x-data="{ disabilityAndDeafConnections: @js(old('disability_and_deaf', $individual->extra_attributes->get('disability_and_deaf_connections', false))) }">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => $individual->isConnector() ? 5 : 4]) }}
                <br />
                {{ __('Groups you can connect to') }}
            </h2>
            <x-interpretation name="{{ __('Groups you can connect to', [], 'en') }}" />
            <p><span class="text-error">*</span> {{ __('means that a field is required.') }}</p>
            <hr class="divider--thick">
            <p class="h4">
                {{ __('Please indicate which groups you can help organizations connect to. An organization may request the services of a Community Connector to assist them in connecting to these groups.') }}
            </p>

            <p>
                {{ __('You don’t need to be a member of these communities yourself.') }}<br />
                {{ __('Selecting some of these options may open up new follow-up questions below them.') }}
            </p>

            <fieldset
                class="field @error('disability_and_deaf') field--error @enderror @error('lived_experience_connections') field--error @enderror">
                <legend>
                    <x-required>{{ __('Can you connect to people with disabilities and Deaf people, their supporters, or both?') }}</x-required>
                </legend>
                <x-interpretation
                    name="{{ __('Can you connect to people with disabilities and Deaf people, their supporters, or both?', [], 'en') }}" />
                <x-hearth-hint
                    for="lived_experience_connections">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <div class="field">
                    <x-hearth-checkbox name="disability_and_deaf" :checked="old(
                        'disability_and_deaf',
                        $individual->extra_attributes->get('disability_and_deaf_connections', false),
                    )"
                        x-model="disabilityAndDeafConnections" hinted="lived_experience_connections-hint" />
                    <x-hearth-label
                        for="disability_and_deaf">{{ __('People with disabilities and/or Deaf people') }}</x-hearth-label>
                </div>
                <x-hearth-checkboxes name="lived_experience_connections" :options="$livedExperiences" :checked="old(
                    'lived_experience_connections',
                    $individual->livedExperienceConnections->pluck('id')->toArray() ?? [],
                )"
                    hinted="lived_experience_connections-hint" required />
                <x-hearth-error for="disability_and_deaf" />
                <x-hearth-error for="lived_experience_connections" />
            </fieldset>

            <div class="stack fieldset" x-show="disabilityAndDeafConnections" x-cloak x-data="{
                baseDisabilityType: @js(old('base_disability_type', $individual->base_disability_type)),
                otherDisability: @js(old('has_other_disability_connection', !blank($individual->other_disability_connection)))
            }">
                <fieldset class="field @error('base_disability_type') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Please select the disability and/or Deaf groups that you can connect to.') }}</x-required>
                    </legend>
                    <x-interpretation
                        name="{{ __('Please select the disability and/or Deaf groups that you can connect to.', [], 'en') }}" />
                    <x-hearth-radio-buttons name="base_disability_type" :options="$baseDisabilityTypes" :checked="old('base_disability_type', $individual->base_disability_type) ?? ''"
                        x-model="baseDisabilityType" />
                    <x-hearth-error for="base_disability_type" />
                </fieldset>
                <fieldset class="field box @error('disability_and_deaf_connections') field--error @enderror"
                    x-show="baseDisabilityType == 'specific_disabilities'" x-cloak>
                    <legend>
                        <x-required>{{ __('Please select the specific disability and/or Deaf groups that you can connect to.') }}</x-required>
                    </legend>
                    <x-interpretation
                        name="{{ __('Please select the specific disability and/or Deaf groups that you can connect to.', [], 'en') }}" />
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="disability_and_deaf_connections" :options="$disabilityTypes" :checked="old(
                        'disability_and_deaf_connections',
                        $individual->disabilityAndDeafConnections->pluck('id')->toArray(),
                    )"
                        required />
                    <div class="field">
                        <x-hearth-checkbox name="has_other_disability_connection" :checked="old(
                            'has_other_disability_connection',
                            !is_null($individual->other_disability_connection) &&
                                $individual->other_disability_connection !== '',
                        )"
                            x-model="otherDisability" />
                        <x-hearth-label
                            for='has_other_disability_connection'>{{ __('Something else') }}</x-hearth-label>
                    </div>
                    <div class="field__subfield stack">
                        <x-translatable-input name="other_disability_connection" :label="__('Disability type')" :shortLabel="__('disability type')"
                            :model="$individual" x-show="otherDisability" x-cloak />
                    </div>
                    <x-hearth-error for="disability_and_deaf_connections" />
                    <x-hearth-error for="has_other_disability_connection" />
                </fieldset>
            </div>

            <fieldset class="field @error('area_type_connections') field--error @enderror">
                <legend><x-required>{{ __('Where do the people that you can connect to come from?') }}</x-required>
                </legend>
                <x-interpretation
                    name="{{ __('Where do the people that you can connect to come from?', [], 'en') }}" />
                <x-hearth-hint for="area_type_connections">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="area_type_connections" :options="$areaTypes" :checked="old('area_type_connections', $individual->areaTypeConnections->pluck('id')->toArray())"
                    hinted="area_type_connections-hint" required />
                <x-hearth-error for="area_type_connections" />
            </fieldset>
            <div class="stack fieldset" x-data="{ hasIndigenousIdentities: @js(old('has_indigenous_connections', $individual->hasConnections('indigenousConnections'))) }">
                <fieldset class="field @error('has_indigenous_connections') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Can you connect to people who are First Nations, Inuit, or Métis?') }}</x-required>
                    </legend>
                    <x-interpretation
                        name="{{ __('Can you connect to people who are First Nations, Inuit, or Métis?', [], 'en') }}" />
                    <x-hearth-radio-buttons name="has_indigenous_connections" :options="$yesNoOptions" :checked="old(
                        'has_indigenous_connections',
                        $individual->hasConnections('indigenousConnections'),
                    ) ?? ''"
                        x-model="hasIndigenousIdentities" />
                    <x-hearth-error for="has_indigenous_connections" />
                </fieldset>

                <fieldset class="field box @error('indigenous_connections') field--error @enderror"
                    x-show="hasIndigenousIdentities == true" x-cloak>
                    <legend><x-required>{{ __('Which Indigenous groups can you connect to?') }}</x-required></legend>
                    <x-interpretation name="{{ __('Which Indigenous groups can you connect to?', [], 'en') }}" />
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="indigenous_connections" :options="$indigenousIdentities" :checked="old(
                        'indigenous_connections',
                        $individual->indigenousConnections->pluck('id')->toArray() ?? [],
                    )" required />
                    <x-hearth-error for="indigenous_connections" />
                </fieldset>
            </div>

            <fieldset class="field @error('refugees_and_immigrants') field--error @enderror">
                <legend><x-required>{{ __('Can you connect to refugees and/or immigrants?') }}</x-required></legend>
                <x-interpretation name="{{ __('Can you connect to refugees and/or immigrants?', [], 'en') }}" />
                <x-hearth-radio-buttons name="refugees_and_immigrants" :options="$yesNoOptions" :checked="old('refugees_and_immigrants', $individual->hasConnections('statusConnections')) ?? ''" />
                <x-hearth-error for="refugees_and_immigrants" />
            </fieldset>

            <div class="stack fieldset" x-data="{ hasGenderAndSexualityConnections: @js(old('has_gender_and_sexuality_connections', $individual->hasConnections('genderAndSexualityConnections'))) }">
                <fieldset class="field @error('has_gender_and_sexuality_connections') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Can you connect to people who are marginalized based on gender or sexual identity?') }}</x-required>
                    </legend>
                    <x-interpretation
                        name="{{ __('Can you connect to people who are marginalized based on gender or sexual identity?', [], 'en') }}" />
                    <x-hearth-radio-buttons name="has_gender_and_sexuality_connections" :options="$yesNoOptions"
                        :checked="old(
                            'has_gender_and_sexuality_connections',
                            $individual->hasConnections('genderAndSexualityConnections'),
                        ) ?? ''" x-model="hasGenderAndSexualityConnections" />
                    <x-hearth-error for="has_gender_and_sexuality_connections" />
                </fieldset>
                <fieldset
                    class="field box @error('gender_and_sexuality_connections') field--error @enderror @error('nb_gnc_fluid_identity') field--error @enderror"
                    x-show="hasGenderAndSexualityConnections == 1" x-cloak>
                    <legend>
                        <x-required>{{ __('Which groups marginalized based on gender or sexual identity can you connect to?') }}</x-required>
                    </legend>
                    <x-interpretation
                        name="{{ __('Which groups marginalized based on gender or sexual identity can you connect to?', [], 'en') }}" />
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <div class="field">
                        <x-hearth-checkbox name="nb_gnc_fluid_identity" :checked="old(
                            'nb_gnc_fluid_identity',
                            $individual->hasConnections('genderDiverseConnections') ?? false,
                        )" />
                        <x-hearth-label
                            for='nb_gnc_fluid_identity'>{{ __('Non-binary, gender non-conforming and/or gender fluid people') }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkboxes name="gender_and_sexuality_connections" :options="$genderAndSexualIdentities"
                            :checked="old(
                                'gender_and_sexuality_connections',
                                $individual->genderAndSexualityConnections->pluck('id')->toArray(),
                            )" required />
                    </div>
                    <x-hearth-error for="gender_and_sexuality_connections" />
                </fieldset>
            </div>

            <div class="stack fieldset" x-data="{ hasAgeBrackets: @js(old('has_age_bracket_connections', $individual->hasConnections('ageBracketConnections'))) }">
                <fieldset class="field @error('has_age_bracket_connections') field--error @enderror">
                    <legend><x-required>{{ __('Can you connect to a specific age bracket or brackets?') }}</x-required>
                    </legend>
                    <x-interpretation
                        name="{{ __('Can you connect to a specific age bracket or brackets?', [], 'en') }}" />
                    <x-hearth-radio-buttons name="has_age_bracket_connections" :options="$yesNoOptions" :checked="old(
                        'has_age_bracket_connections',
                        $individual->hasConnections('ageBracketConnections'),
                    ) ?? ''"
                        x-model="hasAgeBrackets" />
                    <x-hearth-error for="has_age_bracket_connections" />
                </fieldset>
                <fieldset class="field box @error('age_bracket_connections') field--error @enderror"
                    x-show="hasAgeBrackets == true" x-cloak>
                    <legend>
                        <x-required>{{ __('Which age groups can you connect to?') }}</x-required>
                    </legend>
                    <x-interpretation name="{{ __('Which age groups can you connect to?', [], 'en') }}" />
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="age_bracket_connections" :options="$ageBrackets" :checked="old(
                        'age_bracket_connections',
                        $individual->ageBracketConnections->pluck('id')->toArray(),
                    )"
                        required />
                    <x-hearth-error for="age_bracket_connections" />
                </fieldset>
            </div>

            <div class="stack fieldset" x-data="{
                hasEthnoracialIdentities: @js(old('has_ethnoracial_identity_connections', $individual->hasConnections('ethnoracialIdentityConnections') || !blank($individual->other_ethnoracial_identity_connection) ? true : false)),
                otherEthnoracialIdentity: @js(old('other_ethnoracial', !blank($individual->other_ethnoracial_identity_connection)))
            }">
                <fieldset class="field @error('has_ethnoracial_identity_connections') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Can you connect to a specific ethnoracial identity or identities?') }}</x-required>
                    </legend>
                    <x-interpretation
                        name="{{ __('Can you connect to a specific ethnoracial identity or identities?', [], 'en') }}" />
                    <x-hearth-radio-buttons name="has_ethnoracial_identity_connections" :options="$yesNoOptions"
                        :checked="old(
                            'has_ethnoracial_identity_connections',
                            $individual->hasConnections('ethnoracialIdentityConnections') ||
                            !blank($individual->other_ethnoracial_identity_connection)
                                ? true
                                : false,
                        )" x-model="hasEthnoracialIdentities" />
                    <x-hearth-error for="has_ethnoracial_identity_connections" />
                </fieldset>
                <fieldset class="field box @error('ethnoracial_identity_connections') field--error @enderror"
                    x-show="hasEthnoracialIdentities == 1" x-cloak>
                    <legend>
                        <x-required>{{ __('Which ethno-racial identity or identities are the people you can connect to?') }}</x-required>
                    </legend>
                    <x-interpretation
                        name="{{ __('Which ethno-racial identity or identities are the people you can connect to?', [], 'en') }}" />
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="ethnoracial_identity_connections" :options="$ethnoracialIdentities" :checked="old(
                        'ethnoracial_identity_connections',
                        $individual->ethnoracialIdentityConnections->pluck('id')->toArray(),
                    )"
                        required />
                    <div class="field">
                        <x-hearth-checkbox name="has_other_ethnoracial_identity_connection" :checked="old(
                            'has_other_ethnoracial_identity_connection',
                            !blank($individual->other_ethnoracial_identity_connection),
                        )"
                            x-model="otherEthnoracialIdentity" />
                        <x-hearth-label
                            for='has_other_ethnoracial_identity_connection'>{{ __('Something else') }}</x-hearth-label>
                    </div>
                    <div class="field__subfield stack">
                        <x-translatable-input name="other_ethnoracial_identity_connection" :label="__('Ethnoracial identity')"
                            :shortLabel="__('ethnoracial identity')" :model="$individual" x-show="otherEthnoracialIdentity" x-cloak />
                    </div>
                    <x-hearth-error for="ethnoracial_identity_connections" />
                </fieldset>
            </div>

            <fieldset class="field @error('language_connections') field--error @enderror">
                <legend><x-optional>{{ __('What languages are used by the people you can connect to?') }}</x-optional>
                </legend>
                <x-interpretation
                    name="{{ __('What languages are used by the people you can connect to?', [], 'en') }}" />
                <livewire:language-picker name="language_connections" :languages="$individual->languageConnections->pluck('code')->toArray() ?? []" :availableLanguages="$languages" />
                <x-hearth-error for="language_connections" />
            </fieldset>

            <fieldset class="field @error('connection_lived_experience') field--error @enderror">
                <legend>
                    <x-required>{{ __('Do you have lived experience of the people you can connect to?') }}</x-required>
                </legend>
                <x-interpretation
                    name="{{ __('Do you have lived experience of the people you can connect to?', [], 'en') }}" />
                <x-hearth-radio-buttons name="connection_lived_experience" :options="$communityConnectorHasLivedExperience" :checked="old('connection_lived_experience', $individual->connection_lived_experience)" />
                <x-hearth-error for="connection_lived_experience" />
            </fieldset>
            <hr class="divider--thick">
            <p class="flex flex-wrap gap-7">
                <button class="secondary" name="previous" value="1">{{ __('Save and previous') }}</button>
                <button class="secondary" name="save" value="1">{{ __('Save') }}</button>
                <button name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>

</form>
