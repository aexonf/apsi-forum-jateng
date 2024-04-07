import React, { useState } from "react";
import { ChevronLeft } from "lucide-react";
import Layout from "@/components/elements/layout";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Link } from "@inertiajs/inertia-react";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";

import ReactQuill from "react-quill";
import "react-quill/dist/quill.snow.css";

export default function CreateForum() {
    const [value, setValue] = useState("");

    const handleInputChange = (value) => {
        setValue(value);
    };

    const handleSubmit = (event) => {
        event.preventDefault();
        console.log(value);
    };
    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <Button variant="ghost" size="icon" asChild>
                    <Link href="/">
                        <ChevronLeft />
                    </Link>
                </Button>
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Tambah Forum
                </h3>
                <span></span>
            </div>
            <div className="w-full flex flex-col gap-y-4">
                <Card className="w-full bg-white p-4 rounded-lg shadow-md">
                    <CardContent>
                        <div className="flex items-center space-x-4 my-4">
                            <Avatar>
                                <AvatarImage alt="User profile picture" />
                                <AvatarFallback>EP</AvatarFallback>
                            </Avatar>
                            <div>
                                <div className="font-semibold text-lg">
                                    Eka Prasetyo S.Pd
                                </div>
                                <div className="text-gray-500">
                                    Kepala Sekolah SMK Bintara
                                </div>
                            </div>
                        </div>
                        <form
                            className="flex flex-col space-y-4"
                            onSubmit={handleSubmit}
                        >
                            <div className="flex flex-col space-y-2">
                                <Label htmlFor="title">Judul</Label>
                                <Input id="title" placeholder="Title" />
                            </div>
                            <div className="flex flex-col space-y-2">
                                <Label htmlFor="content">Konten</Label>
                                <ReactQuill
                                    theme="snow"
                                    value={value}
                                    onChange={handleInputChange}
                                />
                            </div>
                            <Button
                                className="bg-primary text-primary-foreground"
                                type="submit"
                            >
                                Tambah Forum
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </Layout>
    );
}
