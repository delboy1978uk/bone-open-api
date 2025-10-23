model Href {
    href: string;
}

model HalLink {
    self: Href;
}

model HalLinks extends HalLink{
    first?: Href;
    last?: Href;
    next?: Href;
    prev?: Href;
}

model HalCollection<T> {
    _links: HalLinks;
    _embedded: Array<T>;
}

model HalRecord<T> {
    _links: HalLink;
    ...T;
}

model HasId {
    @visibility(Lifecycle.Read)
    @key
    id: int32;
}

model Timestamps extends HasId {
    @visibility(Lifecycle.Read)
    createdAt: string;

    @visibility(Lifecycle.Read)
    updatedat?: string;
}
