<div class="faqs-list">
    @foreach($categories as $categoryIndex => $category)
        @if (count($categories) > 1)
            <h4>{{ $category->name }}</h4>
        @endif
        <div class="accordion" id="faq-accordion-{{ $categoryIndex }}">
            @foreach($category->faqs as $faqIndex => $faq)
                <div class="card">
                    <div class="card-header" id="heading-faq-{{ $categoryIndex }}-{{ $faqIndex }}">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left @if (!($categoryIndex == 0 && $faqIndex == 0)) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-faq-{{ $categoryIndex }}-{{ $faqIndex }}" aria-expanded="true" aria-controls="collapse-faq-{{ $categoryIndex }}-{{ $faqIndex }}">
                                {!! BaseHelper::clean($faq->question) !!}
                            </button>
                        </h2>
                    </div>

                    <div id="collapse-faq-{{ $categoryIndex }}-{{ $faqIndex }}" class="collapse @if ($categoryIndex == 0 && $faqIndex == 0) show @endif" aria-labelledby="heading-faq-{{ $categoryIndex }}-{{ $faqIndex }}" data-parent="#faq-accordion-{{ $categoryIndex }}">
                        <div class="card-body">
                            {!! BaseHelper::clean($faq->answer) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
